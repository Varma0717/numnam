# Quick Start Guide - NumNam Mobile App

## 🚀 Installation on Android Phone

### Step 1: Locate the APK File
Once the build completes, find your APK at:
```
C:\xampp\htdocs\numnam-api\mobile-app\build\app\outputs\flutter-apk\app-arm64-v8a-release.apk
```

**Which APK to use?**
- **app-arm64-v8a-release.apk** ← Use this for most modern Android phones (2019+)
- **app-armeabi-v7a-release.apk** ← For older Android phones (2015-2019)
- **app-x86_64-release.apk** ← For Android emulators/tablets

### Step 2: Transfer APK to Your Phone
**Option A: USB Cable**
1. Connect phone to PC with USB cable
2. Copy APK file to your phone's Downloads folder
3. Enable "File Transfer" mode on your phone if prompted

**Option B: Cloud/Email**
1. Upload APK to Google Drive/Dropbox
2. Download on your phone
3. Or email it to yourself and download

### Step 3: Install the APK
1. Open **Settings** on your Android phone
2. Go to **Security** (or **Apps & notifications** → **Special app access**)
3. Enable **"Install unknown apps"** for your file manager or Chrome
4. Navigate to Downloads folder
5. Tap the APK file
6. Tap **Install**
7. Tap **Open** when installation completes

---

## 🔑 Login to the App

### Using Existing Account
If you already have a numnam.com account:
1. Open NumNam app
2. Tap **Login**
3. Enter your **email** and **password**
4. Tap **Sign In**

### Create New Account
If you need a new account:
1. Open NumNam app
2. Tap **Sign Up** or **Create Account**
3. Fill in your details:
   - Name
   - Email
   - Password
   - Phone number
4. Tap **Register**
5. Login with your new credentials

---

## ✅ Features to Test

### 1. Browse Products
- View home screen with featured products
- Tap categories (4-6mo, 6-9mo, 9-12mo, 12+mo)
- Use search to find specific products
- Apply filters (age group, price, popularity)

### 2. Product Details
- Tap any product card
- View full product images (swipe gallery)
- Read description & nutrition facts
- Check stock availability
- Select quantity with + / - buttons

### 3. Add to Cart
- Tap **Add to Cart** button on product page
- View cart icon badge (shows item count)
- Go to **Cart** tab from bottom navigation

### 4. Shopping Cart
- View all items in cart
- Update quantities with + / -
- Remove items by tapping trash icon
- See price totals update in real-time
- Tap **Proceed to Checkout**

### 5. Checkout & Payment
- Fill in delivery information:
  - Full name
  - Phone number
  - Address, City, State, Pincode
- Choose payment method:
  - **Cash on Delivery** (COD)
  - **Pay with Razorpay** (Card/UPI/Netbanking)
- Apply coupon code (optional)
- Tap **Place Order**
- For Razorpay: Complete payment in payment gateway

### 6. Subscribe to Plans  
- Go to **Subscriptions** tab
- View available subscription plans
- Tap **Subscribe Now** on any plan
- Fill in delivery details
- Choose payment method
- Complete subscription purchase
- View confirmation with renewal date

### 7. View Orders
- Tap menu icon (☰) or profile icon
- Select **My Orders**
- View order history with status
- Tap any order to see details

### 8. Manage Wishlist
- Tap heart icon (♡) on any product
- Go to **Wishlist** from menu
- View saved products
- Remove items or add to cart

### 9. Edit Profile
- Go to **Account** tab
- Tap profile section
- Update name, email, phone
- Upload profile photo
- Change password
- Manage delivery addresses

---

## 🐛 Troubleshooting

### App Won't Install
**Error: "App not installed"**
- Solution: Enable "Install unknown apps" in Settings → Security
- Or try: Settings → Apps → Special access → Install unknown apps

**Error: "Parse error"**
- Solution: Downloaded APK might be corrupted. Download again.
- Or use different APK variant (arm64 vs armeabi)

### Can't Login
**Error: "Invalid credentials"**
- Solution: Check if you're using the correct numnam.com account email/password
- Try "Forgot Password" to reset

**Error: "Network error"**
- Solution: Check internet connection (WiFi or mobile data)
- App needs internet to connect to numnam.com

### Images Not Loading
- Check internet connection speed
- Wait a few seconds for images to cache
- Restart app if images still don't appear

### Payment Failed
**Razorpay payment not completing:**
- Check if Razorpay key is configured correctly
- Try COD payment method instead
- Check card/UPI balance

### Subscription Not Activating
- Check "My Account" → "Subscriptions" to verify status
- Payment might need time to process (wait 1-2 minutes)
- Contact support if issue persists

---

## 📊 Database Verification

To verify everything is working correctly with the production database:

### Check Products
1. Browse products in the app
2. All products should have images loaded
3. Verify stock quantities show correctly
4. Product prices should match website

### Check Orders After Purchase
1. Complete a test order (use small amount)
2. Check "My Orders" in app
3. Verify order appears with correct:
   - Order number
   - Items purchased
   - Total amount
   - Delivery address
   - Order status

### Check Subscriptions
1. Subscribe to a plan
2. Go to Account → Subscriptions
3. Verify subscription shows:
   - Plan name
   - Active status
   - Renewal date
   - Billing cycle

---

## 🔧 Advanced Options

### Clear App Data
If app behaves strangely:
1. Settings → Apps → NumNam
2. Storage → Clear Data
3. Reopen app and login again

### Check App Version
1. Open NumNam app
2. Go to Account/Profile
3. Scroll to bottom
4. Version should show: **1.0.0+1**

### Report Bugs
If you encounter issues:
1. Note the exact steps to reproduce
2. Take screenshot of error (if any)
3. Check if it's listed in "Known Issues" section of IMPLEMENTATION_SUMMARY.md

---

## 📱 iOS Build (Coming Soon)

To build iOS version on your Mac mini:

### On Mac:
```bash
cd /path/to/numnam-api/mobile-app
flutter pub get
cd ios && pod install && cd ..
flutter build ios --release
```

### Or use Xcode:
1. Open `ios/Runner.xcworkspace` in Xcode
2. Select your Apple Developer team
3. Product → Archive
4. Distribute App → App Store Connect

---

## 💡 Tips

1. **Test with real data carefully**: The app connects to production database at numnam.com
2. **Mark test orders**: Create products/plans with "TEST" prefix to distinguish from real orders
3. **Payment testing**: Use test Razorpay account or small amounts
4. **Performance**: First load may be slower as images cache. Subsequent loads will be faster.
5. **Updates**: To update app, rebuild APK and install over existing app (data won't be lost)

---

## 📞 Support

If you need help:
- Check `IMPLEMENTATION_SUMMARY.md` for detailed technical info
- Review error messages in app
- Verify internet connection
- Restart app if things seem stuck

---

**Build Date**: May 16, 2026
**App Version**: 1.0.0+1
**Flutter Version**: 3.41.0
**Platforms**: Android ✅ | iOS (pending Mac build)
