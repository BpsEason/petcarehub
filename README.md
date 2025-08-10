# PetCareHub

集中管理寵物照護，並透過 AI 推薦提升效率與體驗

PetCareHub 是一個全端解決方案，結合 Laravel API、Vue 3 網頁端、Flutter 手機 App 以及 Flask AI 推薦微服務，幫助飼主輕鬆規劃、記錄並優化每日寵物照護任務。

---

## 主要亮點

- AI 驅動推薦  
  利用 Flask 架設的微服務分析飼主行為與寵物狀況，動態產生最適化照護建議與排程。  

- 多端同步  
  Laravel REST API 作為核心，統一管理資料與邏輯；Vue 3 提供直觀的網頁 Dashboard；Flutter 原生 App 支援 iOS/Android，隨時掌握照護進度。  

- 模組化微服務架構  
  各職責服務獨立部署，使用 Docker 容器化管理，彈性擴增與維護更高效。  

- CI/CD 與自動化測試  
  整合 GitHub Actions 實現自動化建構、測試與部署，確保每次更新品質穩定。  

- 事件驅動與訊息佇列  
  使用 RabbitMQ（或 Kafka）串聯各微服務，非同步處理通知、排程與分析，提升系統可擴展性與穩定度。  

---

## 核心功能

- 任務排程與提醒  
- 照護記錄與歷史查詢  
- AI 建議：餵食量、洗澡頻率、運動時長  
- 團隊協作：多人帳號與權限控管  
- API 文件：OpenAPI/Swagger 規範

---

## 系統架構概覽

```
┌─────────────┐      ┌────────────┐
│  Flutter    │      │   Vue 3    │
│  Mobile App │◀────▶│  Web UI    │
└─────────────┘      └────────────┘
        ▲                   ▲
        │                   │
        └───────▶ Laravel API ───────▶ Docker Registry
                   │
                   ▼
             Flask AI 微服務
                   │
                   ▼
               資料庫 / MQ
```
# PetCareHub

集中管理寵物照護，並透過 AI 推薦提升效率與體驗

PetCareHub 是一個全端解決方案，結合 Laravel API、Vue 3 網頁端、Flutter 手機 App 以及 Flask AI 推薦微服務，幫助飼主輕鬆規劃、記錄並優化每日寵物照護任務。

---

## 主要亮點

- AI 驅動推薦  
  利用 Flask 架設的微服務分析飼主行為與寵物狀況，動態產生最適化照護建議與排程。  

- 多端同步  
  Laravel REST API 作為核心，統一管理資料與邏輯；Vue 3 提供直觀的網頁 Dashboard；Flutter 原生 App 支援 iOS/Android，隨時掌握照護進度。  

- 模組化微服務架構  
  各職責服務獨立部署，使用 Docker 容器化管理，彈性擴增與維護更高效。  

- CI/CD 與自動化測試  
  整合 GitHub Actions 實現自動化建構、測試與部署，確保每次更新品質穩定。  

- 事件驅動與訊息佇列  
  使用 RabbitMQ（或 Kafka）串聯各微服務，非同步處理通知、排程與分析，提升系統可擴展性與穩定度。  

---

## How to Use

1. Clone 專案到本地  
   ```bash
   # 下載程式碼
   git clone https://github.com/BpsEason/petcarehub.git

   # 進入專案根目錄
   cd petcarehub
   ```

2. 後端設定  
   ```bash
   # 複製範例環境變數檔
   cp .env.example .env

   # 安裝 PHP 相依套件
   composer install

   # 產生應用程式金鑰
   php artisan key:generate

   # 執行資料庫遷移並種子資料
   php artisan migrate --seed
   ```

3. 啟動服務  
   ```bash
   # 使用 Docker Compose 啟動 API、MQ 與 AI 微服務
   docker-compose up -d
   ```

4. 前端 Web  
   ```bash
   cd web
   npm install         # 安裝 Node.js 相依套件
   npm run dev         # 啟動開發伺服器 (http://localhost:5173)
   ```

5. 行動端 App  
   ```bash
   cd mobile
   flutter pub get     # 下載 Flutter 套件
   flutter run         # 在模擬器或實機上執行
   ```

---

## 範例關鍵代碼

下面範例展示如何在不同層級呼叫「建立新寵物」API，並附上關鍵註解。

### Laravel Route 定義
```php
// routes/api.php

// 建立新的寵物資源，對應 PetController@store 方法
Route::post('/pets', [App\Http\Controllers\PetController::class, 'store']);
```

### JavaScript / Vue 3 呼叫範例
```js
// src/api/pets.js

// 建立寵物 API 呼叫函式
export async function createPet(pet) {
  const res = await fetch(`${import.meta.env.VITE_API_URL}/pets`, {
    method: 'POST',                // 使用 POST 方法建立資源
    headers: {
      'Content-Type': 'application/json',    // 傳送 JSON 格式資料
      'Authorization': `Bearer ${token}`,    // JWT 驗證
    },
    body: JSON.stringify(pet),     // 將寵物物件轉成字串
  });

  if (!res.ok) {
    throw new Error('Failed to create pet');  // 簡易錯誤處理
  }

  return res.json();              // 回傳伺服器回應的 JSON
}
```

### Flutter / Dart 呼叫範例
```dart
// lib/services/api_service.dart

import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/pet.dart';

class ApiService {
  final String baseUrl = 'http://localhost:8000/api';
  final String token = 'YOUR_JWT_TOKEN_HERE';

  Future<Pet> createPet(Pet pet) async {
    final response = await http.post(
      Uri.parse('$baseUrl/pets'),
      headers: {
        'Content-Type': 'application/json',     // 設定請求格式為 JSON
        'Authorization': 'Bearer $token',        // 帶入 JWT Token
      },
      body: jsonEncode(pet.toJson()),            // 將 Pet 物件轉 JSON
    );

    if (response.statusCode == 201) {
      return Pet.fromJson(jsonDecode(response.body));  // 成功時解析回傳資料
    } else {
      throw Exception('無法建立寵物');               // 失敗時丟出例外
    }
  }
}
```
---

## 技術棧

- 後端 API：Laravel (PHP)  
- 網頁前端：Vue 3 + Pinia + Vite  
- 行動端：Flutter + Dart  
- AI 推薦：Flask (Python) + scikit-learn / TensorFlow  
- 容器化：Docker & Docker Compose  
- 訊息佇列：RabbitMQ / Kafka  
- CI/CD：GitHub Actions  
- 資料庫：MySQL / PostgreSQL

---

更多細節與使用範例，請參考 `/docs` 目錄與專案 Wiki。若想參與開發或提出建議，歡迎查看 CONTRIBUTING.md。
