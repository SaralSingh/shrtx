# 🚀 Shrtx — API-First URL Shortener

**Shrtx** is an **API-first URL shortening service** built with **Laravel**, designed to be:

* Simple to integrate
* Secure by default
* Optimized for high-frequency redirect traffic
* Deterministic and collision-free

It follows a **production-oriented backend design** with controlled creation and ultra-fast public redirects.

> **Design Version:** `Shrtx-AFCA v1`  
> (API-First Controlled Access)

---

## 🧠 Design Philosophy

Shrtx is built with real backend system thinking:

* ✅ API-first (no frontend dependency)
* ✅ Deterministic short-code generation
* ✅ Public redirects, controlled creation
* ✅ Read-heavy optimization
* ✅ Clean separation of concerns
* ✅ No randomness, no retry loops

---

## ✨ Features

| Feature           | Description                              |
| ----------------- | ---------------------------------------- |
| 🔗 URL Shortening | Create short URLs via API                |
| ⚡ Fast Redirects  | Public, highly optimized redirects       |
| 🧮 Base62 Codes   | Deterministic ID → Base62 encoding       |
| 🚫 Collision-Free | Zero risk of duplicate short codes       |
| 🔒 Rate Limited   | Anonymous and token-based throttling     |
| 🔑 Sanctum Ready  | Token authentication for trusted clients |
| 📊 Click Tracking | Track hits per short URL                 |
| ⏳ Expiration      | Optional expiry for links                |

---

## 🏗 Architecture Overview

### Creation Flow

`Client
  ↓
POST /api/shorten  (rate limited)
  ↓
Controller (thin)
  ↓
Service Layer
  ↓
Database (MySQL)
  ↓
Base62 Encoder
`

### Redirect Flow

`GET /{shortCode}
  ↓
Indexed DB lookup
  ↓
302 Redirect
`

---

## 🔑 Short Code Strategy (Core Idea)

Shrtx uses a **deterministic ID-based approach**:

`short_code = Base62(database_id)
`

### Why this is powerful

* No randomness
* No collisions
* No retries
* Predictable at scale
* Extremely easy to debug

| DB ID | Short Code |
| ----- | ---------- |
| 1     | 1          |
| 10    | a          |
| 62    | 10         |
| 125   | cb         |

This behavior is **intentional and correct**.

---

## 🌐 API Endpoints

### ➕ Create Short URL (Anonymous — Limited)

**POST** `/api/shorten`

**Headers**

`Content-Type: application/json
Accept: application/json
`

**Body**

`{
  "url": "https://example.com"
}
`

**Response — 201 Created**

`{
  "short_url": "http://127.0.0.1:8000/1",
  "code": "1",
  "original_url": "https://example.com"
}
`

---

### ➕ Create Short URL (Authenticated — Extended)

**POST** `/api/shorten`

`Authorization: Bearer <API_TOKEN>
`

* Uses Laravel Sanctum
* Higher rate limits
* For trusted clients / services

---

### 🔁 Redirect (Public)

**GET** `/{shortCode}`

Example:

`GET /1
`

* No authentication
* No token
* Instant 302 redirect

---

## ⏱ Rate Limiting

Shrtx uses **named rate limiters**.

| Access Type   | Limit                              |
| ------------- | ---------------------------------- |
| Anonymous     | 10 requests / minute (IP-based)    |
| Authenticated | 60 requests / minute (token-based) |

If exceeded:

`429 Too Many Requests
`

---

## 🗄 Database Schema

### `short_urls` table

| Column        | Type                 | Purpose           |
| ------------- | -------------------- | ----------------- |
| id            | BIGINT               | Primary key       |
| original\_url | TEXT                 | Long URL          |
| short\_code   | VARCHAR(10)          | Base62 encoded ID |
| clicks        | BIGINT               | Redirect count    |
| expires\_at   | TIMESTAMP (nullable) | Optional expiry   |
| created\_at   | TIMESTAMP            | Created time      |
| updated\_at   | TIMESTAMP            | Updated time      |

* `short_code` is **unique & indexed**
* Schema supports deterministic generation

---

## 🔐 Security Considerations

* Only `http` and `https` URLs allowed
* Tokens never exposed to browsers
* API protected via rate limiting
* Redirects are public **by design**
* Strict input validation at service layer

---

## ⚠️ Intentional Design Decisions

### Same long URL → Multiple short URLs

Shrtx is **non-idempotent** by design.

Each request:

* Creates a new short URL
* Enables independent analytics
* Avoids extra DB lookups
* Keeps API semantics clean

> This is **by design**, not a bug.

---

## 🧪 Testing Guide

### API Testing

Use Postman / curl:

`POST /api/shorten
`

Send JSON body.

### Redirect Testing

Open short URL in browser.

### Throttle Testing

Exceed limits → receive `429 Too Many Requests`.

---

## 🚀 Tech Stack

* Laravel 12
* PHP 8.2
* MySQL
* Laravel Sanctum
* Base62 Encoding

---

## 🛣 Future Enhancements

* Redis caching for redirects
* Async click tracking
* Custom short codes
* Optional deduplication flag
* Token management dashboard
* Analytics API

---

## 📌 Project Status

| Status     | State              |
| ---------- | ------------------ |
| Core Logic | ✅ Complete         |
| API        | ✅ Tested           |
| Redirect   | ✅ Working          |
| Throttling | ✅ Verified         |
| Design     | ✅ Production-ready |

---

## 👤 Author

**Hyper**  
Backend-focused developer building real-world API systems.