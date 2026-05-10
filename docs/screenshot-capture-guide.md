# NumNam Mobile App - Screenshot Capture Guide

## Overview

This guide provides step-by-step instructions for capturing production-quality screenshots for Google Play Store and Apple App Store submissions.

---

## Prerequisites

### Hardware

- **Mac Mini** (required for iOS builds and iPhone screenshots)
- **Physical Android device** (Pixel, Samsung, or OnePlus recommended)
- **Physical iPhone** (iPhone 14 Pro Max or 13 Pro Max for 6.5" screenshots)
- **Good lighting** (natural or well-lit environment)

### Software

- Flutter SDK (v3.41.0+)
- Xcode (latest stable version)
- Android Studio or Android SDK command-line tools
- Image editing software (optional: Figma, Photoshop, Canva for overlay text)

### App Configuration

- **Production API:** Ensure `mobile-app/.env.production` has `API_BASE_URL=https://numnam.com/api/v1`
- **Live Data:** Production API should have real products, images, and content (no placeholders)
- **Test Account:** Create a test customer account with sample orders and subscriptions

---

## Part 1: Build the App for Screenshots

### A. Build for Android

1. **Navigate to mobile app directory:**

   ```powershell
   cd C:\xampp\htdocs\numnam-api\mobile-app
   ```

2. **Ensure production environment:**

   ```powershell
   # Verify .env or .env.production has production API URL
   cat .env.production
   # Should show: API_BASE_URL=https://numnam.com/api/v1
   ```

3. **Build release APK:**

   ```powershell
   flutter build apk --release --dart-define-from-file=.env.production
   ```

   Output location: `build/app/outputs/flutter-apk/app-release.apk`

4. **Transfer APK to Android device:**
   - Connect device via USB
   - Enable USB debugging in Developer Options
   - Transfer APK: `adb install build/app/outputs/flutter-apk/app-release.apk`
   - Or upload to Google Drive and download on device

### B. Build for iOS (on Mac Mini)

1. **SSH into Mac Mini or work directly:**

   ```bash
   cd /path/to/numnam-api/mobile-app
   ```

2. **Install dependencies:**

   ```bash
   flutter pub get
   cd ios
   pod install
   cd ..
   ```

3. **Open Xcode project:**

   ```bash
   open ios/Runner.xcworkspace
   ```

4. **Configure signing in Xcode:**
   - Select **Runner** project → **Signing & Capabilities** tab
   - Choose your **Team** and **Bundle Identifier**
   - Ensure **Automatically manage signing** is enabled

5. **Build for physical iPhone:**
   - Connect iPhone via USB
   - Select iPhone as target device in Xcode
   - Click **Product** → **Build** (⌘B)
   - Click **Product** → **Run** (⌘R) to install on device

6. **Alternative: Build without Xcode:**

   ```bash
   flutter build ios --release --dart-define-from-file=.env.production
   # Then deploy via Xcode or ios-deploy tool
   ```

---

## Part 2: Prepare Test Data

### Before Capturing Screenshots

1. **Create test customer account:**
   - Email: <screenshot@numnam.com> (or any test email)
   - Name: Test User
   - Phone: 9876543210

2. **Place 2-3 test orders:**
   - Mix of different products
   - At least one order in "Delivered" status
   - One order in "Processing" status for variety

3. **Create test subscription:**
   - Subscribe to any pricing plan (monthly/weekly)
   - Status: Active

4. **Add items to cart:**
   - 3-4 different products in cart
   - Mix of quantities (1x, 2x, etc.)

5. **Add items to wishlist:**
   - 2-3 products saved for later

6. **Populate profile:**
   - Full name, phone, address filled out
   - Complete shipping address with city/state/pincode

---

## Part 3: Capture Screenshots

### Google Play Store Requirements

- **Resolution:** 1080 x 2340 pixels (portrait, phone)
- **Format:** PNG or JPEG
- **Quantity:** Minimum 2, maximum 8
- **Devices:** Phone screenshots required; tablet optional

### Apple App Store Requirements

- **6.5" Display:** 1284 x 2778 pixels (iPhone 14 Pro Max, 13 Pro Max, 12 Pro Max)
- **5.5" Display:** 1242 x 2208 pixels (iPhone 8 Plus, 7 Plus, 6s Plus)
- **Format:** PNG or JPEG
- **Quantity:** Minimum 3, maximum 10

---

### Screenshot Checklist (Capture in this order)

#### Screenshot 1: Home Screen

**Goal:** Show app landing page with featured products and categories

**Steps:**

1. Open NumNam app (ensure logged out or fresh state)
2. Wait for home screen to fully load
3. Ensure featured products carousel shows real product images
4. Ensure categories section is visible
5. **Capture:** Full screen from status bar to bottom navigation

**What to show:**

- NumNam logo/header
- Featured products carousel with real baby food pouches
- "Shop by Category" section
- Bottom navigation (Home, Shop, Cart, Account)

---

#### Screenshot 2: Shop/Products Listing

**Goal:** Show product catalog with filters

**Steps:**

1. Tap **Shop** tab in bottom navigation
2. Wait for products to load
3. Scroll to show variety of products (don't scroll too far, keep "Shop" header visible)
4. **Capture:** Products grid with multiple items visible

**What to show:**

- "Shop" or "Products" header
- Product cards with images, names, prices
- Product variety (different flavors/types)
- Search bar or filter options (if visible)

---

#### Screenshot 3: Product Detail

**Goal:** Show detailed product page with nutrition info

**Steps:**

1. Tap on any featured product (e.g., "Apple & Banana Puree")
2. Wait for product detail page to load
3. Scroll to show product image, price, description, and "Add to Cart" button
4. **Capture:** Product detail with image at top, info below

**What to show:**

- Large product image
- Product name and price
- Product description
- Ingredients list (if visible)
- "Add to Cart" button
- Nutrition information (optional, depends on scroll position)

---

#### Screenshot 4: Cart

**Goal:** Show cart with multiple items ready for checkout

**Steps:**

1. Ensure cart has 3-4 different products with varying quantities
2. Tap **Cart** tab in bottom navigation
3. Wait for cart to load
4. **Capture:** Cart page showing items, quantities, subtotal

**What to show:**

- "Cart" header
- List of cart items with product images, names, quantities
- Quantity adjustment buttons (+/-)
- Subtotal/total amount
- "Proceed to Checkout" button

---

#### Screenshot 5: Checkout

**Goal:** Show checkout flow with delivery information

**Steps:**

1. From cart, tap **Proceed to Checkout**
2. Fill in delivery address (use test data)
3. Scroll to show address form and order summary
4. **Capture:** Checkout page with address fields and summary

**What to show:**

- "Checkout" header
- Delivery address form fields (name, phone, address, city, pincode)
- Order summary (items, subtotal, shipping, total)
- Payment options (Razorpay logo/button)
- "Place Order" button

---

#### Screenshot 6: Account Dashboard

**Goal:** Show user account with orders and subscriptions tabs

**Steps:**

1. Tap **Account** tab in bottom navigation
2. Ensure logged in with test account that has orders
3. Wait for account dashboard to load
4. **Capture:** Account page showing profile stats and tabs

**What to show:**

- User name/greeting
- Stats cards (Orders, Active Subscriptions, Referrals, Reward Balance)
- Tabs (Profile, Orders, Subscriptions, Referrals, Rewards)
- Profile information (if "Profile" tab is active)

---

#### Screenshot 7: Subscriptions (Active)

**Goal:** Show subscription management with active plan

**Steps:**

1. On Account screen, tap **Subscriptions** tab
2. Wait for subscription data to load
3. Ensure at least one active subscription is visible
4. **Capture:** Subscriptions list with active plan details

**What to show:**

- "Subscriptions" section/tab
- Active subscription card with plan name, frequency, next billing date
- Subscription status (Active/Paused)
- "Pause" or "Manage" button

---

#### Screenshot 8 (Optional): Blog/Recipes or Order History

**Goal:** Show additional features

**Option A - Order History:**

1. On Account screen, tap **Orders** tab
2. Show list of past orders with status badges
3. **Capture:** Orders list with delivered/processing orders

**Option B - Blog/Recipes:**

1. Navigate to Blog section (if accessible from home or menu)
2. Show blog articles about baby nutrition
3. **Capture:** Blog listing with article thumbnails

---

## Part 4: Screenshot Post-Processing

### A. Organize Screenshots

Create folder structure:

```
mobile-app/
  screenshots/
    android/
      phone/
        1-home.png
        2-shop.png
        3-product-detail.png
        4-cart.png
        5-checkout.png
        6-account.png
        7-subscriptions.png
        8-orders.png
    ios/
      6.5-inch/
        1-home.png
        2-shop.png
        3-product-detail.png
        4-cart.png
        5-checkout.png
        6-account.png
        7-subscriptions.png
        8-orders.png
      5.5-inch/
        (same files, resized for 5.5" display)
```

### B. Resize/Crop Screenshots (if needed)

**Android (Play Store):**

- Target: 1080 x 2340 pixels (or native device resolution)
- Use Figma, Photoshop, or online tools to resize if needed
- Maintain aspect ratio

**iOS (App Store):**

- **6.5" Display:** 1284 x 2778 pixels
- **5.5" Display:** 1242 x 2208 pixels
- Use Xcode Simulator to capture exact sizes if device screenshots don't match
- Alternative: Use [AppLaunchpad](https://theapplaunchpad.com/) or [Shotbot](https://shotbot.io/) for automated resizing

### C. Add Overlay Text (Optional but Recommended)

Use Figma or Canva to add text overlays that highlight key features:

**Example overlays:**

- Screenshot 1 (Home): "Browse Doctor-Formulated Baby Food"
- Screenshot 2 (Shop): "Real Ingredients. No Preservatives."
- Screenshot 3 (Product): "Detailed Nutrition & Ingredient Info"
- Screenshot 4 (Cart): "Easy Checkout. Secure Payment."
- Screenshot 5 (Checkout): "Fast Delivery Across India"
- Screenshot 6 (Account): "Manage Orders & Subscriptions"
- Screenshot 7 (Subscriptions): "Subscribe & Save on Deliveries"

**Overlay design tips:**

- Use brand colors (NumNam purple/pink: #E91E63, #9C27B0)
- Use Baloo 2 or Poppins fonts (match app branding)
- Keep text concise (5-7 words max)
- Position text at top or bottom, avoid covering important UI

### D. Quality Check

Before uploading, verify:

- [ ] All screenshots are in correct resolution
- [ ] No placeholder text or lorem ipsum visible
- [ ] All product images loaded (no broken image icons)
- [ ] Screenshots show real production data
- [ ] Status bar shows good signal/battery (or hide status bar)
- [ ] No personal information visible (use test account data only)
- [ ] Text overlays (if used) are readable and aligned
- [ ] All screenshots are in PNG or JPEG format
- [ ] File sizes are reasonable (<5MB each)

---

## Part 5: Create Play Store Feature Graphic

### Specifications

- **Size:** 1024 x 500 pixels
- **Format:** PNG or JPEG (no transparency)
- **Purpose:** Displayed at top of Play Store listing

### Design Concept

1. **Background:** Soft gradient from #FFF0F5 (pink) to white
2. **Hero Image:** Happy baby with NumNam pouch (stock photo or illustration)
3. **Logo:** NumNam logo prominently placed (left or center)
4. **Headline Text:** "Doctor-Made Baby Food. Delivered Fresh."
5. **Subtext (optional):** "Nutritious. Convenient. Trusted by Parents."
6. **Call-to-Action:** "Shop Now" button visual or arrow

### Tools

- **Figma** (recommended, free online tool)
- **Canva** (easy templates)
- **Adobe Photoshop/Illustrator**

### Assets Needed

- NumNam logo: `mobile-app/assets/images/logo.png`
- Baby/parent stock photos (use Unsplash, Pexels, or licensed images)
- Brand colors: Pink (#FFF0F5, #FFD6E5), Purple (#9C27B0), Green (#4CAF50)

---

## Part 6: Upload Screenshots to Stores

### Google Play Console

1. Go to [Google Play Console](https://play.google.com/console/)
2. Select **NumNam** app
3. Navigate to **Store presence** → **Main store listing**
4. Scroll to **Phone screenshots** section
5. Click **Upload** and select your 7-8 screenshots in order
6. Upload **Feature graphic** (1024x500px)
7. **Save** changes

### Apple App Store Connect

1. Go to [App Store Connect](https://appstoreconnect.apple.com/)
2. Select **NumNam** app
3. Click on version (e.g., **1.0.0**)
4. Scroll to **App Previews and Screenshots**
5. Select **6.5" Display** → Upload screenshots in order
6. Select **5.5" Display** → Upload same screenshots (resized)
7. Add **App Preview Video** (optional, 15-30 seconds)
8. **Save** changes

---

## Part 7: Troubleshooting

### Common Issues

**Issue:** Screenshots too large (>5MB)

- **Solution:** Compress using [TinyPNG](https://tinypng.com/) or export at 80-90% quality

**Issue:** Wrong resolution

- **Solution:** Use online tools like [Resize Image](https://resizeimage.net/) to exact dimensions

**Issue:** App crashes during screenshot capture

- **Solution:** Clear app data, restart app, ensure stable internet for API calls

**Issue:** Blank product images (images not loading)

- **Solution:** Check production API is accessible, verify image URLs in backend, test on device with good internet

**Issue:** Can't install on iPhone (signing error)

- **Solution:** Re-configure signing in Xcode, ensure provisioning profile is valid, trust developer certificate on device (Settings → General → VPN & Device Management)

---

## Part 8: Final Checklist

Before submitting to stores:

- [ ] Captured all 7-8 required screenshots for Play Store
- [ ] Captured all 7-8 required screenshots for App Store (6.5" display)
- [ ] Captured/resized 7-8 screenshots for App Store (5.5" display)
- [ ] Created Play Store feature graphic (1024x500px)
- [ ] Added overlay text to screenshots (optional but recommended)
- [ ] Verified all screenshots show real production data (no placeholders)
- [ ] Organized screenshots in numbered order (1-home, 2-shop, etc.)
- [ ] Compressed screenshots to reduce file size if needed
- [ ] Quality-checked all screenshots (no blurriness, cropping issues, or errors)
- [ ] Uploaded screenshots to Play Console and App Store Connect
- [ ] Uploaded feature graphic to Play Console
- [ ] Saved changes in both consoles

---

## Timeline Estimate

- **Setup & Build:** 30-45 minutes (first time), 15 minutes (subsequent builds)
- **Prepare Test Data:** 15-20 minutes
- **Capture Screenshots:** 30-45 minutes (7-8 screens × 2 platforms)
- **Post-Processing:** 45-60 minutes (resize, add overlays, quality check)
- **Feature Graphic Design:** 60-90 minutes (if creating from scratch)
- **Upload to Stores:** 15-20 minutes

**Total:** ~4-5 hours for first-time complete screenshot set

---

## Tips for Best Results

1. **Use Real Devices:** Emulators work but physical devices show true colors and performance
2. **Natural Lighting:** Avoid harsh shadows if photographing screens
3. **Clean Screenshots:** Close all other apps, clear notifications, ensure full battery/signal icons
4. **Test Data Variety:** Show different product types, order statuses, subscription plans
5. **Update Regularly:** Refresh screenshots with each major app update or UI redesign
6. **A/B Test:** Consider creating 2 sets of screenshots to test which drives more downloads
7. **Localization:** Plan for localized screenshots if expanding to non-English markets

---

## Resources

- [Google Play Screenshot Guidelines](https://support.google.com/googleplay/android-developer/answer/9866151)
- [Apple App Store Screenshot Guidelines](https://developer.apple.com/app-store/product-page/)
- [Figma](https://www.figma.com/) - Free design tool
- [Canva](https://www.canva.com/) - Easy graphic design
- [TinyPNG](https://tinypng.com/) - Image compression
- [Shotbot](https://shotbot.io/) - Automated screenshot framing

---

**Good luck with your screenshot capture! 📸**
