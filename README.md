感謝抽空查看：
- 題目一：
```
SELECT    
    b.id AS bnb_id,
    b.name AS bnb_name,
    SUM(o.amount) AS may_amount
FROM 
    orders o
INNER JOIN   -- 將orders和bnbs兩張表bnb_id連結，取得旅宿名稱
    bnbs b ON o.bnb_id = b.id
WHERE 
    o.currency = 'TWD'
    AND o.created_at BETWEEN '2023-05-01' AND '2023-05-31'
GROUP BY 
    b.id, b.name
ORDER BY 
    may_amount DESC
LIMIT 10;
```
- 題目二：
  1. 在orders表的bnb_id,currency,created_at欄位上建立複合索引，加速查詢。
  2. 在bnbs表的id欄位上建立索引，加速JOIN操作。

## API 實作測驗流程概述
1. **OrderController**：<將它單純處理http請求>
   - 接收到來自前端的 HTTP 請求。
   - 將請求傳遞給 `OrderService` 進行業務邏輯處理。
   
2. **OrderService**：<邏輯判斷會放到這邊處理>
   - 接收並處理業務邏輯。首先，它會調用 `Validator` 層來檢查請求資料是否符合驗證規則。
   - 經過驗證後，若資料有效，會進行金額計算並返回處理結果。
   
3. **Validator**：
   - 作為一個 **Strategy Pattern** 和 **Chain of Responsibility Pattern** 的應用，`Validator` 層負責處理所有驗證邏輯。
   - 每個欄位（如 `name`、`price`、`currency`）都有其對應的驗證規則，並按照設定的順序進行驗證。
   - 這些驗證規則的應用使得資料驗證過程可以靈活擴展，並保持清晰的結構。
   
4. **回傳結果**：
   - 如果資料驗證成功，`OrderService` 會進行金額計算，並將結果返回給 `OrderController`，最終返回給用戶端。
   - 若驗證失敗，會將錯誤訊息回傳，提示用戶哪些欄位未通過驗證。

## 設計模式

- **Strategy Pattern**：
  - `Validator` 作為策略模式的一部分，負責對不同的欄位（如 `name`、`price`、`currency`）應用不同的驗證策略。
  - 這使得每個驗證邏輯的實現可以獨立，並且在未來可以根據需求更換或擴展驗證邏輯。

- **Chain of Responsibility Pattern)**：
  - 每個欄位的驗證邏輯獨立運作，並按照順序執行，當其中某個欄位驗證失敗時，會立刻返回錯誤並終止後續處理。
  - 這種設計讓不同的驗證規則解耦，並可以很容易地添加新的驗證步驟。

## 單元測試
1. **name**：
   - 不可以出現非英文字，會返回錯誤訊息。
   - 首字母不是大寫，會返回錯誤訊息。

2. **price**：
   - 若 `price` 超過最大值 2000，會返回錯誤訊息。

3. **currency**：
   - 必須為 `TWD` 或 `USD` 其中之一。
   - 若 `currency` 格式無效，會返回錯誤訊息。
<img width="777" alt="截圖 2024-11-21 凌晨1 33 18" src="https://github.com/user-attachments/assets/e16d3cc1-6ae4-422d-8152-86bae3e0c0d3">
     
