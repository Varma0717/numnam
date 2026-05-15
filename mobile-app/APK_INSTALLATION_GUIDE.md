# 📱 NumNam APK - Installation Guide

## 🎯 **What's Building:**

Your **production-ready Android APK** with:

- ✅ All new redesigned screens (Home, Shop, Product Detail, Cart, Subscriptions, Account)
- ✅ Connected to **production database** at <https://numnam.com/api/v1>
- ✅ Live Razorpay payment gateway
- ✅ Real products, orders, and customer data

---

## 📦 **APK Location**

Once the build completes, your APK will be at:

```
C:\xampp\htdocs\numnam-api\mobile-app\build\app\outputs\flutter-apk\app-release.apk
```

**File size:** ~20-50 MB (depending on assets)

---

## 📲 **Installation Steps**

### **Step 1: Transfer APK to Your Phone**

**Method A: USB Cable**

1. Connect your Android phone to your PC via USB
2. Copy `app-release.apk` to your phone's `Downloads` folder

**Method B: Cloud Storage**

1. Upload `app-release.apk` to Google Drive / Dropbox
2. Download it on your phone

**Method C: Direct File Share**

1. Email the APK to yourself
2. Open email on your phone and download the attachment

### **Step 2: Enable "Install from Unknown Sources"**

On your Android phone:

1. Go to **Settings** → **Security** (or **Privacy**)
2. Find **"Install unknown apps"** or **"Unknown sources"**
3. Select the app you'll use to install (e.g., **Files**, **Chrome**, **Gmail**)
4. Toggle **"Allow from this source"** to ON

**Note:** Location varies by Android version:

- **Android 8+:** Settings → Apps → Special Access → Install Unknown Apps
- **Android 7 and below:** Settings → Security → Unknown Sources (toggle ON)

### **Step 3: Install the APK**

1. Open your **Files** app (or **Downloads** folder)
2. Locate **app-release.apk**
3. Tap on the APK file
4. Tap **"Install"**
5. Wait for installation to complete (~10-30 seconds)
6. Tap **"Open"** or find **NumNam** app in your app drawer

---

## 🔐 **Login to Production Database**

### **Your App is Connected to Production:**

```
API: https://numnam.com/api/v1
Database: Production MySQL
Payments: Live Razorpay
```

### **Login Credentials:**

**Use your real numnam.com account:**

- Email: Your registered email on numnam.com
- Password: Your actual password

**Don't have an account?**

- Tap "Sign Up" / "Create Account" in the app
- Or register at <https://numnam.com> first

---

## 🎨 **What You'll See - New Screens**

### **1. 🚀 Splash Screen**

- Beautiful NumNam logo animation (3 seconds)

### **2. 📖 Onboarding (First Time Only)**

- 3-page introduction to NumNam
- Swipe through pages
- Tap "Get Started" to begin

### **3. 🔐 Login/Register**

- Enter your production credentials
- Or create a new account

### **4. 🏠 Home Screen (NEW REDESIGN!)**

- ✨ Auto-scrolling hero banners
- 🍼 Age-based categories (4-6, 6-9, 9-12, 12+ months)
- 🏆 Featured products grid
- 💳 Subscription plan previews
- ⭐ Best sellers section

### **5. 🛍️ Shop Screen (NEW REDESIGN!)**

- 🔍 Search bar
- 🏷️ Category filter button
- 📊 Sort options (Popular, Price, New Arrivals)
- 🔄 Grid/List view toggle
- 📦 Product cards with real data

### **6. 📦 Product Detail (NEW REDESIGN!)**

- 🖼️ Swipeable image gallery
- ➕➖ Quantity selector
- 🛒 Add to Cart button
- 📊 Nutrition information
- ⭐ Customer reviews
- 📝 Full product description

### **7. 🛒 Cart Screen (NEW REDESIGN!)**

- 🛍️ Cart items with images
- ➕➖ Update quantity
- 🗑️ Remove items
- 💰 Subtotal and total
- ✅ Checkout button

### **8. 💳 Subscriptions Screen (NEW REDESIGN!)**

- 🎁 Benefits (Save Money, Free Delivery, Fresh, Flexible)
- 📦 Three plans:
  - **Monthly:** ₹1,999/month
  - **Quarterly:** ₹5,499/3 months ⭐ POPULAR
  - **Annual:** ₹19,999/year
- 🎯 Subscribe Now buttons

### **9. 👤 Account Screen**

- Profile information
- Order history (real orders)
- Settings
- Logout

---

## 🎨 **Design Features**

Your new app includes:

✅ **Modern Color Scheme:**

- 🔴 Coral #FF6B8A (Primary)
- 💛 Yellow #FFD93D (Secondary)
- 💚 Mint #4ECDC4 (Accent)
- 💜 Lavender #9B8EC4 (Tertiary)
- 🤍 Cream #FFFCF5 (Background)

✅ **Professional Typography:**

- Baloo2 font for headers
- Poppins font for body text

✅ **Performance Optimizations:**

- Cached network images
- Smooth animations
- Fast navigation

✅ **Bottom Navigation Bar:**

- 🏠 Home
- 🛍️ Shop
- 🛒 Cart
- 💳 Subscribe
- 👤 Account

---

## ⚠️ **Important - Production Mode**

Your app is connected to the **LIVE production database**:

### **What's Real:**

- ✅ All products from your database
- ✅ Real customer accounts
- ✅ Actual order creation
- ✅ Live payment processing
- ✅ Real inventory updates

### **Test Safely:**

- ✅ Browse products (safe)
- ✅ Search and filter (safe)
- ✅ View product details (safe)
- ✅ Add to cart (safe - just creates cart)
- ⚠️ **Place orders (creates REAL orders!)**
- ⚠️ **Process payments (charges REAL money!)**

**Be careful during checkout!**

---

## 🔧 **Troubleshooting**

### **Issue: "App not installed"**

- ✅ Enable "Unknown sources" in Settings
- ✅ Delete old version of NumNam app first
- ✅ Ensure enough storage space (~100 MB free)
- ✅ Try restarting your phone

### **Issue: "Cannot connect to server"**

- ✅ Check internet connection
- ✅ Verify <https://numnam.com> is accessible
- ✅ Try switching between WiFi and mobile data

### **Issue: "Invalid credentials"**

- ✅ Verify your email is correct
- ✅ Check password (case-sensitive)
- ✅ Reset password on numnam.com if needed
- ✅ Try registering a new account

### **Issue: "No products showing"**

- ✅ Check internet connection
- ✅ Pull down to refresh
- ✅ Verify products exist in production database

### **Issue: "Images not loading"**

- ✅ Check internet connection
- ✅ Verify image URLs in database
- ✅ Wait for images to cache (first load may be slow)

---

## 🎉 **You're Ready!**

Once installed, you'll have:

- ✅ Beautifully redesigned mobile app
- ✅ Connected to production database
- ✅ Full ecommerce functionality
- ✅ Working cart and checkout
- ✅ Subscription management
- ✅ Live payment gateway
- ✅ Professional UI/UX

**Enjoy your new NumNam app!** 🚀

---

## 📞 **Need Help?**

Check your build terminal for:

```
✓ Built build\app\outputs\flutter-apk\app-release.apk
```

This means your APK is ready to install!

**File location:**

```
C:\xampp\htdocs\numnam-api\mobile-app\build\app\outputs\flutter-apk\app-release.apk
```

Transfer this file to your Android phone and install it! 📱
