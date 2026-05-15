# NumNam Mobile App - Redesign Documentation

## 🎨 Overview

This redesign transforms the NumNam mobile app into a modern, professional ecommerce platform with subscription capabilities. The new design features:

- **Modern UI/UX** with engaging animations and smooth interactions
- **Enhanced Product Discovery** with filters, search, and categories
- **Beautiful Product Details** with image galleries and nutrition info
- **Streamlined Cart & Checkout** for better conversion
- **Subscription Management** with plan comparison
- **Onboarding Experience** to welcome new users

---

## 📱 New Screens

### 1. **Splash Screen** (`splash_screen.dart`)

**Purpose**: App launch screen with animated branding

**Features**:

- Animated logo with scale and fade effects
- Gradient background (Coral to Yellow)
- Automatic navigation after 2-3 seconds
- Smooth transition to onboarding or main app

**Usage**:

```dart
// In main.dart or app.dart
runApp(const SplashScreen());
```

---

### 2. **Onboarding Screen** (`onboarding_screen.dart`)

**Purpose**: Introduce app features to first-time users

**Features**:

- 3 beautiful pages highlighting key benefits:
  1. Fresh & Organic ingredients
  2. Age-appropriate nutrition
  3. Subscribe & Save benefits
- Swipeable pages with page indicators
- Skip button for quick access
- "Get Started" button on final page

**Key Benefits Highlighted**:

- 🥄 Fresh & Organic food
- 👶 Age-appropriate nutrition (4-12+ months)
- 📦 Subscribe & Save up to 25%

---

### 3. **Home Screen Redesign** (`home_screen_redesign.dart`)

**Purpose**: Main landing page with featured content

**Features**:

- **Hero Banner Carousel**: Auto-scrolling banners with CTA buttons
  - Banner 1: Nutritious Baby Food
  - Banner 2: Subscribe & Save
  - Banner 3: Fresh & Healthy
- **Shop by Age Categories**: Quick navigation cards
  - 4-6 Months 👶
  - 6-9 Months 🍼
  - 9-12 Months 🥄
  - 12+ Months 🍽️
- **Trust Badges**: Organic, Lab Tested, No Additives
- **Featured Products Grid**: 2-column grid of popular items
- **Subscription Plans**: Horizontal scrollable cards
- **Best Sellers**: Compact horizontal list

**Data Sources**:

- `GET /api/v1/products?featured=1` - Featured products
- `GET /api/v1/products?sort=popular` - Best sellers
- `GET /api/v1/pricing-plans` - Subscription plans

**Banner Images** (to be added):

- `assets/images/banner1.png`
- `assets/images/banner2.png`
- `assets/images/banner3.png`

---

### 4. **Shop Screen Redesign** (`shop_screen_redesign.dart`)

**Purpose**: Product catalog with advanced filtering

**Features**:

- **Search Bar**: Real-time product search
- **Category Filter**: Shop by age group
  - All Products
  - 4-6 Months
  - 6-9 Months
  - 9-12 Months
  - 12+ Months
- **Sort Options**:
  - Most Popular
  - Newest First
  - Price: Low to High
  - Price: High to Low
  - Name: A-Z
- **View Toggle**: Switch between grid and list view
- **Product Cards**: Show image, name, price, sale badge, stock status

**Filter Bottom Sheets**:

- Category selection with radio buttons
- Sort options with visual selection
- Smooth animations and transitions

---

### 5. **Product Detail Screen Redesign** (`product_detail_screen_redesign.dart`)

**Purpose**: Comprehensive product information

**Features**:

- **Image Gallery**:
  - Full-screen expandable images
  - Swipeable product photos
  - Image counter (1/3, 2/3, etc.)
  - Sale badge overlay
- **Product Information**:
  - Age group badge
  - Product name and description
  - Price with sale price crossed out
  - Stock status indicator
- **Quantity Selector**:
  - +/- buttons
  - Stock limit validation
  - Visual quantity display
- **Detailed Sections**:
  - Description with rich text
  - Ingredients list
  - Nutrition facts table
  - Customer reviews (with ratings)
- **Add to Cart Button**:
  - Fixed bottom button
  - Loading state
  - Success feedback
  - Out of stock handling

**Nutrition Table**:
Displays nutrition info in a clean, bordered table format

**Reviews**:

- User avatar
- Star ratings
- Review text
- Limited to 3 visible reviews

---

### 6. **Cart Screen Redesign** (`cart_screen_redesign.dart`)

**Purpose**: Shopping cart management

**Features**:

- **Empty Cart State**:
  - Icon illustration
  - Friendly message
  - "Continue Shopping" button
- **Cart Items**:
  - Product image thumbnail
  - Name and price
  - Sale price indication
  - Quantity controls (+/-/delete)
  - Real-time total updates
- **Price Summary**:
  - Subtotal
  - Discount (if applicable)
  - Total with prominent display
- **Checkout Button**:
  - Fixed bottom position
  - Lock icon for security
  - Navigate to checkout

**Cart Provider Integration**:

- Uses `CartProvider` for state management
- Real-time updates
- Optimistic UI updates

---

### 7. **Subscriptions Screen Redesign** (`subscriptions_screen_redesign.dart`)

**Purpose**: Subscription plan selection and management

**Features**:

- **Hero Section**:
  - Large emoji icon (📦)
  - "Subscribe & Save" title
  - Value proposition
- **Benefits Section**:
  - 💰 Save More (up to 25% off)
  - 🚚 Free Delivery
  - ✨ Fresh & Organic
  - ⏰ Flexible (cancel anytime)
- **Plan Cards**:
  - **Popular Badge** for recommended plans
  - Gradient background for popular plans
  - Plan name and description
  - Large price display with billing cycle
  - Feature list with checkmarks
  - "Subscribe Now" CTA button
  - Products included (with chips)
- **Responsive Design**:
  - Vertical scrollable list
  - Full-width cards
  - Shadow effects for depth

**Plan Card Variations**:

- Standard: White background, coral accents
- Popular: Gradient background (Coral → Yellow), white text

---

## 🎨 Design System

### Color Palette

```dart
kCoral = Color(0xFFFF6B8A)      // Primary brand color
kYellow = Color(0xFFFFD93D)     // Secondary/accent color
kMint = Color(0xFF4ECDC4)       // Success/positive
kLavender = Color(0xFF9B8EC4)   // Tertiary accent
kCream = Color(0xFFFFFCF5)      // Background
kNavy = Color(0xFF1A1A2E)       // Text/dark elements
```

### Typography

- **Headers**: Baloo 2 (playful, friendly)
- **Body Text**: Poppins (clean, readable)
- **Weights**: 500 (regular), 600 (semibold), 700 (bold), 800/900 (heavy)

### Border Radius

- Cards: 16-24px
- Buttons: 12-16px
- Small elements: 8-12px
- Circular: 999px or `shape: BoxShape.circle`

### Spacing

- Small: 4-8px
- Medium: 12-16px
- Large: 20-24px
- XLarge: 32-40px

### Shadows

```dart
BoxShadow(
  color: kCoral.withOpacity(0.2),
  blurRadius: 20,
  offset: Offset(0, 10),
)
```

---

## 🖼️ Required Images

Create these placeholder images in `mobile-app/assets/images/`:

### Banner Images (1080x500px recommended)

1. **banner1.png** - Baby food bowl with colorful puree
2. **banner2.png** - Subscription box with products
3. **banner3.png** - Fresh vegetables and fruits

### Product Images

- Products should have images uploaded via admin panel
- Images served from backend: `product.imageUrl`
- Gallery images: `product.galleryUrls`

### Logo

- **logo.png** - App logo (already exists)

---

## 🔌 Integration Guide

### Step 1: Add to App Routes

In `app.dart`, import the new screens:

```dart
import 'features/splash/splash_screen.dart';
import 'features/onboarding/onboarding_screen.dart';
import 'features/home/home_screen_redesign.dart';
import 'features/shop/shop_screen_redesign.dart';
import 'features/shop/product_detail_screen_redesign.dart';
import 'features/cart/cart_screen_redesign.dart';
import 'features/subscriptions/subscriptions_screen_redesign.dart';
```

### Step 2: Update Shell Navigation

Replace the old screens in the `_Shell` widget:

```dart
class _Shell extends StatefulWidget {
  static final shellKey = GlobalKey<_ShellState>();
  const _Shell({super.key});
  
  @override
  State<_Shell> createState() => _ShellState();
}

class _ShellState extends State<_Shell> {
  int _currentIndex = 0;

  final _screens = const [
    HomeScreenRedesign(),        // New home screen
    ShopScreenRedesign(),         // New shop screen
    CartScreenRedesign(),         // New cart screen
    SubscriptionsScreenRedesign(), // New subscriptions screen
    AccountScreen(),              // Keep existing account screen
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: IndexedStack(
        index: _currentIndex,
        children: _screens,
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex,
        onTap: (index) => setState(() => _currentIndex = index),
        type: BottomNavigationBarType.fixed,
        backgroundColor: Colors.white,
        selectedItemColor: kCoral,
        unselectedItemColor: kNavy.withOpacity(0.5),
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Home'),
          BottomNavigationBarItem(icon: Icon(Icons.shopping_bag), label: 'Shop'),
          BottomNavigationBarItem(icon: Icon(Icons.shopping_cart), label: 'Cart'),
          BottomNavigationBarItem(icon: Icon(Icons.card_membership), label: 'Subscribe'),
          BottomNavigationBarItem(icon: Icon(Icons.person), label: 'Account'),
        ],
      ),
    );
  }
}
```

### Step 3: Update Product Detail Navigation

In existing code that navigates to product details, use:

```dart
Navigator.push(
  context,
  MaterialPageRoute(
    builder: (_) => ProductDetailScreenRedesign(productId: product.id),
  ),
);
```

### Step 4: Add Onboarding Flow

In `main.dart` or app startup:

```dart
class NumNamApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      home: FutureBuilder<bool>(
        future: _checkFirstTime(), // Check if first app launch
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const SplashScreen();
          }
          
          if (snapshot.data == true) {
            return const OnboardingScreen(); // First time user
          }
          
          return _Shell(); // Returning user
        },
      ),
    );
  }

  Future<bool> _checkFirstTime() async {
    final prefs = await SharedPreferences.getInstance();
    final isFirstTime = prefs.getBool('first_time') ?? true;
    if (isFirstTime) {
      await prefs.setBool('first_time', false);
    }
    return isFirstTime;
  }
}
```

---

## 📦 Dependencies

Ensure these are in `pubspec.yaml`:

```yaml
dependencies:
  flutter:
    sdk: flutter
  google_fonts: ^6.2.1
  provider: ^6.1.5
  dio: ^5.9.2
  cached_network_image: ^3.4.1
  flutter_dotenv: ^5.2.1
  razorpay_flutter: ^1.4.5
  flutter_secure_storage: ^9.2.4
  shared_preferences: ^2.3.5  # For onboarding check
```

---

## 🎯 API Endpoints Used

### Products

- `GET /api/v1/products` - List all products
  - Query params: `per_page`, `featured`, `sort`, `age_group`
- `GET /api/v1/products/{id}` - Product details
- `GET /api/v1/products/{id}/reviews` - Product reviews

### Cart

- `POST /api/v1/cart` - Add item to cart
- `GET /api/v1/cart` - Get cart contents
- `PUT /api/v1/cart/{id}` - Update cart item
- `DELETE /api/v1/cart/{id}` - Remove from cart

### Subscriptions

- `GET /api/v1/pricing-plans` - List subscription plans

### Orders

- `POST /api/v1/orders` - Place order
- `GET /api/v1/orders` - Order history

---

## ✨ Key Features

### 1. **Pull to Refresh**

All main screens support pull-to-refresh:

```dart
RefreshIndicator(
  color: kCoral,
  onRefresh: _load,
  child: ListView(...),
)
```

### 2. **Loading States**

Consistent loading indicators:

```dart
if (_loading)
  CircularProgressIndicator(color: kCoral)
```

### 3. **Empty States**

Friendly empty state messages with icons and CTAs

### 4. **Error Handling**

Try-catch blocks with graceful fallbacks

### 5. **Optimistic UI**

Cart updates happen immediately, then sync with backend

### 6. **Image Caching**

`CachedNetworkImage` for fast image loading

---

## 🚀 Testing Checklist

- [ ] Splash screen displays and navigates correctly
- [ ] Onboarding shows on first launch only
- [ ] Home screen loads featured products
- [ ] Category cards navigate to filtered shop
- [ ] Shop screen filters and sorting work
- [ ] Product detail shows all information
- [ ] Add to cart works and shows feedback
- [ ] Cart displays items and calculates totals
- [ ] Quantity controls update cart
- [ ] Checkout navigation works
- [ ] Subscription plans display correctly
- [ ] All images load properly
- [ ] Navigation between screens is smooth
- [ ] Back button behavior is correct
- [ ] Pull to refresh updates data

---

## 📱 Screen Flow

```
Splash Screen
    ↓
First Time User? → Yes → Onboarding → Login/Register
    ↓ No
Main App (Bottom Nav)
    ├─ Home
    │   ├─ Banner → Shop
    │   ├─ Category → Shop (filtered)
    │   ├─ Product Card → Product Detail
    │   └─ Subscription Card → Subscriptions
    ├─ Shop
    │   ├─ Search Products
    │   ├─ Filter by Category
    │   ├─ Sort Products
    │   └─ Product Card → Product Detail
    ├─ Product Detail
    │   ├─ Add to Cart
    │   └─ View Nutrition/Reviews
    ├─ Cart
    │   ├─ Update Quantities
    │   ├─ Remove Items
    │   └─ Proceed to Checkout → Checkout
    ├─ Subscriptions
    │   └─ Subscribe Now → Checkout
    └─ Account
        ├─ Orders
        ├─ Profile
        └─ Settings
```

---

## 🎨 UI Patterns

### Card Style

```dart
Container(
  decoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.circular(20),
    border: Border.all(color: Color(0xFFFFD6E5), width: 2),
  ),
  child: ...,
)
```

### Button Style

```dart
ElevatedButton(
  style: ElevatedButton.styleFrom(
    backgroundColor: kCoral,
    foregroundColor: Colors.white,
    shape: RoundedRectangleBorder(
      borderRadius: BorderRadius.circular(16),
    ),
    elevation: 0,
  ),
  child: ...,
)
```

### Section Title

```dart
Row(
  children: [
    Container(
      padding: EdgeInsets.all(8),
      decoration: BoxDecoration(
        color: kCoral.withOpacity(0.1),
        borderRadius: BorderRadius.circular(10),
      ),
      child: Icon(icon, color: kCoral),
    ),
    SizedBox(width: 12),
    Text(
      title,
      style: GoogleFonts.baloo2(
        fontSize: 22,
        fontWeight: FontWeight.w800,
        color: kNavy,
      ),
    ),
  ],
)
```

---

## 🎉 Next Steps

1. **Add Banner Images**: Create or source 3 banner images
2. **Test Payment Flow**: Verify Razorpay integration with new checkout
3. **Add Analytics**: Track user interactions
4. **Implement Wishlist**: Add wishlist functionality
5. **Push Notifications**: Order updates and promotions
6. **User Reviews**: Allow users to submit reviews
7. **Social Sharing**: Share products on social media
8. **Referral Program**: Refer friends and earn rewards

---

## 📝 Notes

- All screens use the same color palette for consistency
- Animations are kept subtle for better performance
- Images use lazy loading with placeholders
- Error states provide helpful feedback
- The design is mobile-first but scales well
- All interactive elements have proper tap targets (min 44px)
- Text is readable with proper contrast ratios

---

## 🐛 Known Issues

1. **Banner Images**: Placeholder paths need actual images
2. **Reviews API**: May not exist yet - gracefully handles 404
3. **First Time Check**: Requires SharedPreferences implementation

---

## 💡 Tips

1. **Image Optimization**: Compress banner images for faster loading
2. **Cache Strategy**: CachedNetworkImage handles caching automatically
3. **State Management**: Consider upgrading to Riverpod for complex state
4. **Animations**: Keep animations under 300ms for snappiness
5. **Testing**: Test on both iOS and Android for platform differences

---

**Last Updated**: January 2025  
**Version**: 2.0.0  
**Author**: Senior Flutter Developer
