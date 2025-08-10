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
