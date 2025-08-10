import pytest
from app import app # 導入 Flask app 實例

@pytest.fixture
def client():
    app.config['TESTING'] = True
    with app.test_client() as client:
        yield client

def test_recommendations_endpoint_exists(client):
    """測試 /recommendations 端點是否存在並可回應"""
    response = client.post('/recommendations', json={
        'pet_age_months': 12,
        'pet_weight_kg': 10,
        'species': 'dog'
    })
    assert response.status_code == 200
    assert 'recommendations' in response.json

def test_recommendations_endpoint_returns_list(client):
    """測試 /recommendations 回傳格式為列表"""
    response = client.post('/recommendations', json={
        'pet_age_months': 24,
        'pet_weight_kg': 5,
        'species': 'cat'
    })
    assert response.status_code == 200
    assert isinstance(response.json['recommendations'], list)

def test_recommendations_with_invalid_data_types(client):
    """測試無效輸入的錯誤處理 (資料型態)"""
    response = client.post('/recommendations', json={
        'pet_age_months': 'not_a_number',
        'pet_weight_kg': 10,
        'species': 'dog'
    })
    assert response.status_code == 400
    assert 'error' in response.json
    assert response.json['error'] == 'Invalid input types for age or weight'

def test_recommendations_with_invalid_species(client):
    """測試無效物種的錯誤處理"""
    response = client.post('/recommendations', json={
        'pet_age_months': 12,
        'pet_weight_kg': 10,
        'species': 'invalid_species'
    })
    assert response.status_code == 400
    assert 'error' in response.json
    assert response.json['error'] == 'Invalid species. Must be dog, cat, or other.'

def test_recommendations_for_young_dog(client):
    """測試幼犬的推薦清單"""
    response = client.post('/recommendations', json={
        'pet_age_months': 4,
        'pet_weight_kg': 3,
        'species': 'dog'
    })
    assert response.status_code == 200
    recommendations = response.json['recommendations']
    assert '幼寵疫苗接種提醒' in recommendations
    assert '社會化訓練' in recommendations
    assert '定期梳毛' in recommendations # From dog rule

def test_recommendations_for_old_cat(client):
    """測試老年貓的推薦清單"""
    response = client.post('/recommendations', json={
        'pet_age_months': 96, # 8 years old
        'pet_weight_kg': 6,
        'species': 'cat'
    })
    assert response.status_code == 200
    recommendations = response.json['recommendations']
    assert '老年寵物健康檢查' in recommendations
    assert '關節保健品補充' in recommendations
    assert '每日清潔貓砂' in recommendations # From cat rule

def test_recommendations_for_adult_dog(client):
    """測試成年犬的推薦清單"""
    response = client.post('/recommendations', json={
        'pet_age_months': 36,
        'pet_weight_kg': 20,
        'species': 'dog'
    })
    assert response.status_code == 200
    recommendations = response.json['recommendations']
    assert '定期散步與運動' in recommendations or '監控飲食與飲水' in recommendations # Model-driven
    assert '定期梳毛' in recommendations
    assert '每月洗澡' in recommendations
    assert '幼寵疫苗接種提醒' not in recommendations
    assert '老年寵物健康檢查' not in recommendations
