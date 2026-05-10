# NumNam Mobile App - Build & Deployment Instructions

## Overview

This document provides comprehensive instructions for building and deploying the NumNam mobile app to Google Play Store and Apple App Store.

---

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Android - Build AAB for Play Store](#android---build-aab-for-play-store)
3. [iOS - Build IPA for App Store](#ios---build-ipa-for-app-store)
4. [Play Store Submission](#play-store-submission)
5. [App Store Submission](#app-store-submission)
6. [Post-Launch](#post-launch)
7. [Troubleshooting](#troubleshooting)

---

## Prerequisites

### Development Environment

**Required Software:**

- Flutter SDK v3.41.0+ ([Download](https://flutter.dev/docs/get-started/install))
- Android Studio (for Android builds)
- Xcode 14+ (for iOS builds, macOS only)
- Git (for version control)

**Required Accounts:**

- Google Play Console Developer Account ($25 one-time fee)
- Apple Developer Account ($99/year)

**Required Keys & Certificates:**

- Android: Keystore file for signing APK/AAB
- iOS: Developer certificate and provisioning profile
- Razorpay API keys (live credentials)

### Verify Flutter Installation

```powershell
# Check Flutter version
flutter --version

# Check for issues
flutter doctor

# Expected output should show:
# ✓ Flutter (Channel stable, 3.41.0+)
# ✓ Android toolchain
# ✓ Xcode (macOS only)
# ✓ Connected devices
```

---

## Android - Build AAB for Play Store

### Step 1: Create Signing Key (One-Time Setup)

If you haven't created a signing keystore yet:

```powershell
# Navigate to project root
cd C:\xampp\htdocs\numnam-api\mobile-app

# Create android/app directory if it doesn't exist
New-Item -ItemType Directory -Path android\app -Force

# Generate keystore (use Git Bash or WSL on Windows)
keytool -genkey -v -keystore android/app/numnam-release-key.jks -keyalg RSA -keysize 2048 -validity 10000 -alias numnam-key
```

**Keystore Information to Provide:**

- **Name:** NumNam
- **Organizational Unit:** Development
- **Organization:** NumNam
- **City/Locality:** (Your city)
- **State/Province:** (Your state)
- **Country Code:** IN (for India)
- **Password:** (Choose a strong password and **SAVE IT SECURELY**)

**⚠️ CRITICAL:** Back up `numnam-release-key.jks` and password. If lost, you cannot update the app!

### Step 2: Configure Signing in Android

Create `android/key.properties` file:

```properties
storePassword=YOUR_KEYSTORE_PASSWORD
keyPassword=YOUR_KEY_PASSWORD
keyAlias=numnam-key
storeFile=numnam-release-key.jks
```

**⚠️ IMPORTANT:** Add `android/key.properties` to `.gitignore` to avoid committing secrets!

Update `android/app/build.gradle` to use signing config:

```gradle
// Add before 'android {' block
def keystoreProperties = new Properties()
def keystorePropertiesFile = rootProject.file('key.properties')
if (keystorePropertiesFile.exists()) {
    keystoreProperties.load(new FileInputStream(keystorePropertiesFile))
}

android {
    // ... existing config ...

    signingConfigs {
        release {
            keyAlias keystoreProperties['keyAlias']
            keyPassword keystoreProperties['keyPassword']
            storeFile keystoreProperties['storeFile'] ? file(keystoreProperties['storeFile']) : null
            storePassword keystoreProperties['storePassword']
        }
    }

    buildTypes {
        release {
            signingConfig signingConfigs.release
            // ... other release config ...
        }
    }
}
```

### Step 3: Update App Version

Edit `pubspec.yaml`:

```yaml
version: 1.0.0+1
# Format: VERSION_NAME+BUILD_NUMBER
# Increment BUILD_NUMBER for each release (1, 2, 3, ...)
# Increment VERSION_NAME for feature updates (1.0.0, 1.1.0, 2.0.0, ...)
```

### Step 4: Build AAB (Android App Bundle)

```powershell
# Navigate to mobile app directory
cd C:\xampp\htdocs\numnam-api\mobile-app

# Clean previous builds
flutter clean

# Get dependencies
flutter pub get

# Build release AAB
flutter build appbundle --release --dart-define-from-file=.env.production

# Output location:
# build\app\outputs\bundle\release\app-release.aab
```

**Expected output:**

```
✓ Built build\app\outputs\bundle\release\app-release.aab (XX.XMB).
```

### Step 5: Test AAB Locally (Optional)

```powershell
# Install bundletool (if not installed)
# Download from https://github.com/google/bundletool/releases

# Generate APKs from AAB
java -jar bundletool-all.jar build-apks --bundle=build\app\outputs\bundle\release\app-release.aab --output=numnam.apks --mode=universal

# Extract universal APK
java -jar bundletool-all.jar extract-apks --apks=numnam.apks --output-dir=extracted

# Install on connected device
adb install extracted\universal.apk
```

---

## iOS - Build IPA for App Store

### Step 1: Configure Signing (One-Time Setup)

**On Mac Mini:**

1. **Open Xcode:**

   ```bash
   cd /path/to/numnam-api/mobile-app
   open ios/Runner.xcworkspace
   ```

2. **Configure Signing:**
   - Select **Runner** project in left sidebar
   - Select **Runner** target
   - Go to **Signing & Capabilities** tab
   - Check **Automatically manage signing**
   - Select your **Team** (Apple Developer account)
   - Verify **Bundle Identifier** (e.g., `com.numnam.app`)

3. **Add App Icon:**
   - Verify icons exist in `ios/Runner/Assets.xcassets/AppIcon.appiconset/`
   - Should include all required sizes (20x20 to 1024x1024)

### Step 2: Update App Version

Edit `pubspec.yaml`:

```yaml
version: 1.0.0+1
# VERSION_NAME: 1.0.0 (shown to users)
# BUILD_NUMBER: 1 (internal build number, increment for each upload)
```

**OR** edit in Xcode:

- Runner → General → Identity
- **Version:** 1.0.0
- **Build:** 1

### Step 3: Build IPA (Archive)

**Option A: Using Xcode (Recommended)**

```bash
# On Mac Mini
cd /path/to/numnam-api/mobile-app

# Clean previous builds
flutter clean

# Get dependencies
flutter pub get

# Open Xcode
open ios/Runner.xcworkspace
```

In Xcode:

1. Select **Any iOS Device (arm64)** as target (not Simulator)
2. Go to **Product** → **Archive**
3. Wait for archive to complete (~5-10 minutes)
4. **Organizer** window opens automatically
5. Select the archive → Click **Distribute App**
6. Choose **App Store Connect** → **Upload**
7. Follow prompts to upload IPA

**Option B: Using Command Line**

```bash
# Build release iOS app
flutter build ios --release --dart-define-from-file=.env.production

# Archive with Xcode command-line tools
xcodebuild -workspace ios/Runner.xcworkspace \
  -scheme Runner \
  -sdk iphoneos \
  -configuration Release \
  -archivePath build/ios/archive/Runner.xcarchive \
  archive

# Export IPA
xcodebuild -exportArchive \
  -archivePath build/ios/archive/Runner.xcarchive \
  -exportOptionsPlist ios/ExportOptions.plist \
  -exportPath build/ios/ipa

# IPA location: build/ios/ipa/Runner.ipa
```

**Create `ios/ExportOptions.plist`** (if using command-line method):

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>method</key>
    <string>app-store</string>
    <key>teamID</key>
    <string>YOUR_TEAM_ID</string>
    <key>uploadBitcode</key>
    <false/>
    <key>uploadSymbols</key>
    <true/>
</dict>
</plist>
```

### Step 4: Upload to App Store Connect

**Option A: Via Xcode Organizer (Easiest)**

- Already done in Step 3 if you chose "Upload" option

**Option B: Via Transporter App**

1. Download [Apple Transporter](https://apps.apple.com/app/transporter/id1450874784) from Mac App Store
2. Open Transporter
3. Sign in with Apple Developer account
4. Drag and drop `Runner.ipa` file
5. Click **Deliver**

**Option C: Via Command Line**

```bash
xcrun altool --upload-app -f build/ios/ipa/Runner.ipa \
  --type ios \
  --apiKey YOUR_API_KEY \
  --apiIssuer YOUR_ISSUER_ID
```

---

## Play Store Submission

### Step 1: Create App in Play Console

1. Go to [Google Play Console](https://play.google.com/console/)
2. Click **Create app**
3. Fill in details:
   - **App name:** NumNam - Baby Food Delivered
   - **Default language:** English (India)
   - **App or game:** App
   - **Free or paid:** Free
4. Accept declarations and click **Create app**

### Step 2: Complete App Setup

Navigate through all setup sections:

#### **App Access**

- Select **All functionality is available without restrictions**
- Or provide demo credentials if login required

#### **Ads**

- Select **No, my app does not contain ads** (assuming no ads)

#### **Content Rating**

- Complete questionnaire
- Expected rating: **PEGI 3** or **Everyone**

#### **Target Audience**

- Select **Parenting**
- Age range: Adults (parents/caregivers)

#### **News App**

- Select **No**

#### **COVID-19 Contact Tracing and Status Apps**

- Select **No**

#### **Data Safety**

- Complete data safety form
- Declare what data you collect (email, name, phone, address, order history)
- Explain how data is used (order fulfillment, account management)
- Link to privacy policy: <https://numnam.com/privacy-policy>

#### **Government Apps**

- Select **No**

#### **Financial Features**

- Select **No** (unless offering financial services beyond product sales)

### Step 3: Complete Store Listing

Navigate to **Store presence** → **Main store listing**:

1. **App details:**
   - **App name:** NumNam - Baby Food Delivered
   - **Short description:** (Use from `docs/playstore-metadata.md`)
   - **Full description:** (Use from `docs/playstore-metadata.md`)

2. **App icon:**
   - Upload 512x512px PNG (create from `mobile-app/assets/images/logo.png`)

3. **Phone screenshots:**
   - Upload 7-8 screenshots from `mobile-app/screenshots/android/phone/`
   - Order: Home, Shop, Product Detail, Cart, Checkout, Account, Subscriptions

4. **Feature graphic:**
   - Upload 1024x500px PNG (create as per `docs/screenshot-capture-guide.md`)

5. **App category:**
   - **Category:** Shopping
   - **Tags:** Baby Products, Food & Drink (optional)

6. **Contact details:**
   - **Email:** <customercare@numnam.com>
   - **Phone:** +91-9014252278
   - **Website:** <https://numnam.com>

7. **Privacy policy:**
   - **URL:** <https://numnam.com/privacy-policy>

### Step 4: Create Release

Navigate to **Release** → **Production** → **Create new release**:

1. **Upload AAB:**
   - Click **Upload** → Select `app-release.aab`
   - Wait for upload and processing

2. **Release name:**
   - Auto-filled from version (1.0.0)

3. **Release notes:**

   ```
   🎉 Welcome to NumNam!

   This is our first release, bringing doctor-formulated baby nutrition to your fingertips:

   • Browse our full range of baby food pouches
   • Secure checkout with Razorpay payments
   • Subscribe for regular deliveries
   • Track your orders in real-time
   • Manage account and preferences
   • Contact support directly
   • Free shipping on orders ₹499+

   Thank you for trusting NumNam with your baby's nutrition!
   ```

4. **Click Save** → **Review release**

### Step 5: Submit for Review

1. Review all sections for completeness (green checkmarks)
2. Click **Start rollout to Production**
3. Confirm submission

**Expected review time:** 1-7 days

---

## App Store Submission

### Step 1: Create App in App Store Connect

1. Go to [App Store Connect](https://appstoreconnect.apple.com/)
2. Click **My Apps** → **+** → **New App**
3. Fill in details:
   - **Platform:** iOS
   - **Name:** NumNam - Baby Food Delivered
   - **Primary Language:** English (India)
   - **Bundle ID:** (Select the one configured in Xcode, e.g., `com.numnam.app`)
   - **SKU:** numnam-001 (unique identifier)
   - **User Access:** Full Access

### Step 2: Complete App Information

Navigate to **App Information** section:

1. **Privacy Policy URL:** <https://numnam.com/privacy-policy>
2. **Category:**
   - **Primary:** Shopping
   - **Secondary:** Food & Drink
3. **Content Rights:** Check if you own or have licensed rights to all content
4. **Age Rating:** Click **Edit** → Complete questionnaire → Expected: **4+**

### Step 3: Complete Pricing and Availability

1. **Price:** Free
2. **Availability:** Select countries (India initially, expand later)
3. **Pre-orders:** No (for first release)

### Step 4: Prepare for Submission

Navigate to **1.0 Prepare for Submission**:

#### **App Previews and Screenshots:**

1. Select **6.5" Display** → Upload 7-8 screenshots
2. Select **5.5" Display** → Upload 7-8 screenshots (resized)
3. (Optional) Upload **App Preview Video** (15-30 seconds)

#### **Promotional Text:**

(Use from `docs/appstore-metadata.md`)

#### **Description:**

(Use from `docs/appstore-metadata.md`)

#### **Keywords:**

(Use from `docs/appstore-metadata.md`)

#### **Support URL:**
<https://numnam.com/contact>

#### **Marketing URL:**
<https://numnam.com>

### Step 5: Build and Version Information

1. **Build:** Select the build uploaded from Xcode/Transporter
   - If build not visible, wait 10-30 minutes for processing
   - Ensure build status is "Ready to Submit"

2. **Version Information:**
   - **Version:** 1.0.0
   - **Copyright:** © 2026 NumNam. All rights reserved.

3. **App Review Information:**
   - **Contact Information:**
     - First Name: (Your name)
     - Last Name: (Your last name)
     - Phone: +91-9014252278
     - Email: <customercare@numnam.com>

   - **Demo Account (if needed):**
     - Username: <demo@numnam.com>
     - Password: (provide demo credentials)

   - **Notes:**

     ```
     NumNam is a baby food e-commerce app with Razorpay payment integration.
     
     Test flow:
     1. Browse products from home screen
     2. Add items to cart
     3. Proceed to checkout
     4. Place order (test mode supported)
     5. View orders in Account tab
     
     Production API: https://numnam.com/api/v1
     Support: customercare@numnam.com
     ```

4. **Attachment (optional):** Upload demo video or additional documentation if needed

### Step 6: Export Compliance

1. **Does your app use encryption?**
   - If HTTPS only: Select **No**
   - If custom encryption: Select **Yes** and provide details

2. **Content Rights:** Confirm you have rights to all content

### Step 7: Submit for Review

1. Click **Add for Review** (top right)
2. Review all sections for completeness
3. Click **Submit to App Review**

**Expected review time:** 24-48 hours (can be up to 7 days)

---

## Post-Launch

### Monitor Submissions

**Play Console:**

- Go to **Release** → **Production** → **Releases**
- Check status: In review → Approved → Live

**App Store Connect:**

- Go to **My Apps** → **NumNam** → **Activity**
- Check status: Waiting for Review → In Review → Pending Developer Release → Ready for Sale

### Respond to Review Feedback

If rejected, check **Resolution Center** (Play) or **App Review** notes (App Store) for reasons:

- Address issues
- Re-submit with fixes
- Respond to reviewer if clarification needed

### Release to Users

**Play Store:**

- Once approved, app goes live automatically (or staged rollout if configured)

**App Store:**

- After approval, you can choose:
  - **Manually release this version** (recommended for first release)
  - **Automatically release this version** (goes live immediately)

### Post-Release Tasks

1. **Test live app:**
   - Download from Play Store/App Store
   - Test order placement with real payment
   - Verify subscriptions work correctly

2. **Monitor crash reports:**
   - **Play Console:** Release → Production → Crashes & ANRs
   - **App Store Connect:** Analytics → Crashes

3. **Monitor reviews:**
   - Respond to user reviews within 24-48 hours
   - Address common issues in next update

4. **Track metrics:**
   - Downloads
   - Active users
   - Conversion rate (installs → orders)
   - Retention rate

5. **Plan updates:**
   - Bug fixes (version 1.0.1, 1.0.2)
   - Feature updates (version 1.1.0, 1.2.0)
   - Major releases (version 2.0.0)

---

## Troubleshooting

### Common Android Issues

**Issue:** "App not signed correctly"

- **Solution:** Verify `key.properties` file exists and has correct passwords

**Issue:** "Upload failed: Version code already used"

- **Solution:** Increment build number in `pubspec.yaml` (e.g., `1.0.0+2`)

**Issue:** "APK/AAB size too large"

- **Solution:** Enable ProGuard/R8 minification in `android/app/build.gradle`:

  ```gradle
  buildTypes {
      release {
          minifyEnabled true
          shrinkResources true
      }
  }
  ```

### Common iOS Issues

**Issue:** "Provisioning profile doesn't include signing certificate"

- **Solution:** Re-download provisioning profile in Xcode → Preferences → Accounts

**Issue:** "The app's Info.plist is missing purpose strings"

- **Solution:** Add privacy usage descriptions in `ios/Runner/Info.plist`:

  ```xml
  <key>NSCameraUsageDescription</key>
  <string>We need camera access to upload profile pictures</string>
  <key>NSPhotoLibraryUsageDescription</key>
  <string>We need photo library access to upload images</string>
  ```

**Issue:** "Build archive failed"

- **Solution:** Clean build folder: Product → Clean Build Folder (⇧⌘K)

**Issue:** "Upload to App Store failed"

- **Solution:** Verify Apple Developer account is active and certificates are valid

### Common Review Rejection Reasons

**Play Store:**

- **Privacy policy missing/broken link** → Ensure <https://numnam.com/privacy-policy> is accessible
- **App crashes on launch** → Test thoroughly before submission
- **Misleading screenshots** → Ensure screenshots match actual app functionality

**App Store:**

- **Missing demo account** → Provide working credentials if app requires login
- **Privacy policy missing** → Add URL in App Information section
- **App doesn't match description** → Ensure description accurately reflects app functionality
- **Uses third-party payment (violates IAP rules)** → Clarify Razorpay is for physical goods (exempt from IAP requirement)

---

## Version Management

### Incrementing Version Numbers

**For bug fixes (1.0.0 → 1.0.1):**

```yaml
version: 1.0.1+2  # Increment patch version and build number
```

**For new features (1.0.1 → 1.1.0):**

```yaml
version: 1.1.0+3  # Increment minor version and build number
```

**For major changes (1.1.0 → 2.0.0):**

```yaml
version: 2.0.0+4  # Increment major version and build number
```

**Build number** must always increment (never reuse)!

---

## Security Best Practices

1. **Never commit:**
   - `android/key.properties`
   - `android/app/numnam-release-key.jks`
   - `.env` files with secrets

2. **Backup securely:**
   - Keystore file and password
   - Apple certificates and provisioning profiles
   - API keys

3. **Use environment variables:**
   - Store Razorpay keys in `.env.production`
   - Never hardcode secrets in source code

4. **Enable ProGuard/R8:**
   - Obfuscates code to prevent reverse engineering

5. **Regular updates:**
   - Update dependencies for security patches
   - Monitor for vulnerabilities

---

## Resources

- [Flutter Build and Release for Android](https://docs.flutter.dev/deployment/android)
- [Flutter Build and Release for iOS](https://docs.flutter.dev/deployment/ios)
- [Google Play Console Help](https://support.google.com/googleplay/android-developer)
- [App Store Connect Help](https://developer.apple.com/help/app-store-connect/)
- [Razorpay Integration Guide](https://razorpay.com/docs/payments/payment-gateway/quick-start-guide/)

---

**Good luck with your app submission! 🚀**
