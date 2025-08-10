from flask import Flask, jsonify, request
import pandas as pd
import numpy as np
import lightgbm as lgb
import os

app = Flask(__name__)

# 模擬一個簡單的 LightGBM 模型 (生產環境會從檔案載入)
# 為了讓服務能啟動，這裡建立一個假的模型
def create_dummy_model():
    # 建立一些假資料來訓練模型
    data = {
        'pet_age_months': [1, 5, 12, 24, 60, 90, 120, 3, 30, 72],
        'pet_weight_kg': [1, 3, 10, 20, 15, 8, 25, 2, 12, 18],
        'is_dog': [1, 0, 1, 1, 0, 1, 0, 0, 1, 0],
        'is_cat': [0, 1, 0, 0, 1, 0, 1, 1, 0, 1],
        'task_type': [0, 1, 0, 1, 0, 1, 0, 1, 0, 1] # 0: 餵食/清潔, 1: 散步/體檢
    }
    df = pd.DataFrame(data)

    X = df[['pet_age_months', 'pet_weight_kg', 'is_dog', 'is_cat']]
    y = df['task_type']

    model = lgb.LGBMClassifier(objective='binary', n_estimators=10)
    model.fit(X, y)
    return model

model = create_dummy_model()
print("AI Dummy Model Loaded.")

@app.route('/recommendations', methods=['POST'])
def get_recommendations():
    data = request.json
    pet_age_months = data.get('pet_age_months', 0)
    pet_weight_kg = data.get('pet_weight_kg', 0)
    species = data.get('species', 'unknown').lower()

    if not isinstance(pet_age_months, (int, float)) or not isinstance(pet_weight_kg, (int, float)):
        return jsonify({'error': 'Invalid input types for age or weight'}), 400
    if species not in ['dog', 'cat', 'other']:
        return jsonify({'error': 'Invalid species. Must be dog, cat, or other.'}), 400

    is_dog = 1 if species == 'dog' else 0
    is_cat = 1 if species == 'cat' else 0

    # 使用模型進行預測
    try:
        input_data = pd.DataFrame([[pet_age_months, pet_weight_kg, is_dog, is_cat]], 
                                  columns=['pet_age_months', 'pet_weight_kg', 'is_dog', 'is_cat'])
        prediction_proba = model.predict_proba(input_data)[0]
        
        # 根據預測機率，給出一些推薦
        recommended_tasks = []
        if prediction_proba[1] > 0.5: # 如果散步/體檢機率高 (假定 class 1)
            recommended_tasks.append("定期散步與運動")
        else: # 如果餵食/清潔機率高 (假定 class 0)
            recommended_tasks.append("監控飲食與飲水")
            
        # 簡單規則補充推薦
        if pet_age_months < 6:
            recommended_tasks.append("幼寵疫苗接種提醒")
            recommended_tasks.append("社會化訓練")
        elif pet_age_months > 84: # 7歲以上為老年
            recommended_tasks.append("老年寵物健康檢查")
            recommended_tasks.append("關節保健品補充")
        
        if species == 'cat':
            recommended_tasks.append("每日清潔貓砂")
            recommended_tasks.append("提供抓板防止破壞家具")
        elif species == 'dog':
            recommended_tasks.append("定期梳毛")
            recommended_tasks.append("每月洗澡")
        
        # 去重並排序 (可選)
        recommended_tasks = sorted(list(set(recommended_tasks)))

        return jsonify({
            'pet_age_months': pet_age_months,
            'species': species,
            'predicted_task_prob_class_1': float(prediction_proba[1]), # Prob for task_type 1 (e.g. walk/exam)
            'recommendations': recommended_tasks
        })
    except Exception as e:
        return jsonify({'error': str(e), 'message': 'AI prediction failed.'}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
