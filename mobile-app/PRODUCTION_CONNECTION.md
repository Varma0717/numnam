# 🌐 NumNam Mobile App - Production Database Connection

## ✅ **Current Configuration**

Your mobile app is **CONNECTED TO PRODUCTION**:

```env
API_BASE_URL=https://numnam.com/api/v1
RAZORPAY_KEY_ID=rzp_live_SlijR8nAQaEgVA
```

This means:

- ✅ Real production MySQL database
- ✅ Live API at numnam.com
- ✅ Real Razorpay payment processing
- ✅ Actual customer data and orders

---

## 🔐 **Login with Your Real Account**

### **Option 1: Existing Account**

If you already have an account on numnam.com:

1. Enter your **registered email address**
2. Enter your **actual password**
3. Tap "Login"

### **Option 2: Register New Account**

If you don't have an account yet:

1. Tap "Sign Up" or "Create Account"
2. Fill in your details
3. Complete registration
4. Login with your new credentials

---

## 🎨 **New Redesigned Screens**

Once logged in, you'll see these updated screens:

### **1. 🏠 Home Screen**

- Auto-scrolling hero banners
- Age-based categories (4-6, 6-9, 9-12, 12+ months)
- Featured products from your database
- Subscription plan previews
- Best sellers section

### **2. 🛍️ Shop Screen**

- Search all your products
- Category filtering
- Sort options (Popular, Price, New Arrivals)
- Grid/List toggle views
- Real-time product data

### **3. 📦 Product Detail**

- Product images from your database
- Quantity selector
- Add to cart functionality
- Nutrition information
- Customer reviews
- Product descriptions

### **4. 🛒 Shopping Cart**

- Full cart management
- Quantity controls
- Price breakdown
- Checkout navigation
- Real-time totals

### **5. 💳 Subscriptions**

- Monthly: ₹1,999/month
- Quarterly: ₹5,499/3 months (Popular)
- Annual: ₹19,999/year
- Benefits showcase
- Subscribe functionality

### **6. 👤 Account**

- Profile information
- Order history (real orders)
- Settings
- Logout

---

## ⚠️ **Production Mode Warnings**

Since you're connected to production:

### **Real Impact:**

- 🛒 Orders placed = **Real orders in database**
- 💳 Payments processed = **Real transactions via Razorpay**
- 📊 Inventory changes = **Actual stock updates**
- 📧 Emails sent = **Real customer notifications**

### **Test Safely:**

- ✅ Browse products (safe)
- ✅ View categories (safe)
- ✅ Check product details (safe)
- ⚠️ Add to cart (creates cart entries)
- ⚠️ Place orders (creates real orders!)
- ⚠️ Process payments (charges real money!)

---

## 🔄 **Switch to Development Mode (Optional)**

If you want to test WITHOUT affecting production data:

### **Step 1: Update .env file**

Change from production:

```env
# PRODUCTION (current)
API_BASE_URL=https://numnam.com/api/v1
```

To local development:

```env
# LOCAL DEVELOPMENT
API_BASE_URL=http://10.0.2.2/numnam-api/public/api/v1
```

### **Step 2: Start Local XAMPP**

- Start MySQL
- Start Apache
- Ensure local database has test data

### **Step 3: Rebuild App**

```powershell
cd mobile-app
flutter run -d chrome
```

---

## 🚀 **Running the App**

### **Web (Chrome):**

```powershell
cd c:\xampp\htdocs\numnam-api\mobile-app
flutter run -d chrome
```

### **Android APK:**

```powershell
cd c:\xampp\htdocs\numnam-api\mobile-app
flutter build apk --release
```

APK location:

```
mobile-app\build\app\outputs\flutter-apk\app-release.apk
```

### **iOS (Mac only):**

```bash
cd mobile-app
flutter run -d ios
```

---

## 🔍 **Verify Production Connection**

### **Test API Endpoint:**

Open in browser:

```
https://numnam.com/api/v1
```

Should return:

```json
{"message":"Unauthenticated."}
```

This confirms the API is reachable.

### **Check Network Requests:**

In Chrome DevTools (F12):

1. Go to **Network** tab
2. Login to the app
3. Look for requests to `numnam.com/api/v1/login`
4. Check response status (should be 200 for success)

---

## 💡 **Troubleshooting**

### **Issue: "Cannot connect to API"**

- ✅ Check internet connection
- ✅ Verify <https://numnam.com> is accessible
- ✅ Check API is not in maintenance mode

### **Issue: "Invalid credentials"**

- ✅ Verify email is correct
- ✅ Check password (case-sensitive)
- ✅ Try password reset on numnam.com
- ✅ Ensure account exists in production database

### **Issue: "No products showing"**

- ✅ Check if products exist in production database
- ✅ Verify API endpoint: `GET /api/v1/products`
- ✅ Check Chrome DevTools console for errors

### **Issue: "Payment fails"**

- ✅ Verify Razorpay key is correct
- ✅ Check Razorpay dashboard for errors
- ✅ Ensure Razorpay account is active

---

## 📊 **API Endpoints Being Used**

The app connects to these production endpoints:

```
POST   https://numnam.com/api/v1/login
POST   https://numnam.com/api/v1/register
GET    https://numnam.com/api/v1/products
GET    https://numnam.com/api/v1/products/{id}
GET    https://numnam.com/api/v1/pricing-plans
GET    https://numnam.com/api/v1/cart
POST   https://numnam.com/api/v1/cart/add
PUT    https://numnam.com/api/v1/cart/update/{id}
DELETE https://numnam.com/api/v1/cart/remove/{id}
POST   https://numnam.com/api/v1/orders
POST   https://numnam.com/api/v1/subscriptions
```

All requests include:

- **Headers:** `Authorization: Bearer {token}`
- **Content-Type:** `application/json`

---

## ✅ **What You Have Now**

- ✅ Mobile app connected to **production database**
- ✅ Real-time data from **numnam.com**
- ✅ Live Razorpay payment gateway
- ✅ All new redesigned screens
- ✅ Production-ready functionality

**Just login with your real numnam.com credentials and start using the app!** 🚀

---

## 🎉 **Next Steps**

1. ✅ Login with your production account
2. ✅ Browse products (your real inventory)
3. ✅ Test navigation through all new screens
4. ✅ Verify cart functionality
5. ✅ Check subscription plans display
6. ⚠️ **Be careful with checkout** (creates real orders!)

**Enjoy your beautifully redesigned NumNam app connected to production!** 🎨
