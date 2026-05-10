# NumNam Portal Verification Checklist

## Overview

This checklist helps verify all admin and customer portal flows work correctly before go-live. Complete these tests in a staging or production environment with real data.

---

## Prerequisites

- [ ] Laravel app running and accessible (locally or at <https://numnam.com>)
- [ ] Database populated with test products, categories, users, orders, subscriptions
- [ ] Test admin account created (email: <admin@numnam.com> or similar)
- [ ] Test customer account created (email: <testcustomer@numnam.com> or similar)
- [ ] Mail system configured (queue workers running for email testing)
- [ ] Razorpay test keys configured (for payment testing)

---

## Part 1: Admin Portal Verification

### 1.1 Categories Management

**Controller:** `app/Http/Controllers/Admin/Catalog/CategoryManagementController.php`  
**Route:** `/admin/categories`

- [ ] **List categories:** Navigate to admin categories page, verify list displays with product counts
- [ ] **Search categories:** Use search box (q parameter), verify filtering works
- [ ] **Create category:**
  - [ ] Click "Create Category" button
  - [ ] Fill in: Name, Slug (auto-generated), Image URL, Active status
  - [ ] Submit form
  - [ ] Verify success message: "Category created."
  - [ ] Verify new category appears in list
- [ ] **Edit category:**
  - [ ] Click "Edit" on existing category
  - [ ] Modify name or status
  - [ ] Submit form
  - [ ] Verify success message: "Category updated."
  - [ ] Verify changes reflected in list
- [ ] **Delete category:**
  - [ ] Click "Delete" on a category with no products
  - [ ] Confirm deletion
  - [ ] Verify success message: "Category deleted."
  - [ ] Verify category removed from list

---

### 1.2 Blog Management

**Controller:** `app/Http/Controllers/Admin/BlogManagementController.php`  
**Route:** `/admin/blogs`

- [ ] **List blogs:** Navigate to admin blogs page, verify list with author and category
- [ ] **Search blogs:** Use search box, verify filtering by title works
- [ ] **Filter by status:** Use status dropdown (draft/published), verify filtering works
- [ ] **Create blog post:**
  - [ ] Click "Create Blog" button
  - [ ] Fill in: Title, Slug, Category, Excerpt, Content, Featured Image, Status (published)
  - [ ] Submit form
  - [ ] Verify success message: "Blog post created."
  - [ ] Verify published_at timestamp set (if status=published)
  - [ ] Verify new blog appears in list
- [ ] **Edit blog post:**
  - [ ] Click "Edit" on existing blog
  - [ ] Change status from draft to published
  - [ ] Verify published_at gets set
  - [ ] Submit form
  - [ ] Verify success message: "Blog post updated."
- [ ] **Delete blog post:**
  - [ ] Click "Delete" on a test blog
  - [ ] Confirm deletion
  - [ ] Verify success message: "Blog post deleted."
- [ ] **Bulk actions:**
  - [ ] Select 2-3 blog posts (checkboxes)
  - [ ] Choose bulk action: Publish, Draft, or Delete
  - [ ] Submit
  - [ ] Verify success message: "Bulk action applied to X posts."
  - [ ] Verify status changes applied

---

### 1.3 Blog Categories Management

**Controller:** `app/Http/Controllers/Admin/BlogCategoryController.php`  
**Route:** `/admin/blog-categories`

- [ ] **List blog categories:** Navigate to page, verify list with blog counts
- [ ] **Create blog category:**
  - [ ] Click "Create Category" button
  - [ ] Fill in: Name, Slug, Description, Parent (optional)
  - [ ] Submit form
  - [ ] Verify success message: "Blog category created."
- [ ] **Edit blog category:**
  - [ ] Click "Edit" on existing category
  - [ ] Modify name or parent
  - [ ] Submit form
  - [ ] Verify success message: "Blog category updated."
- [ ] **Delete blog category:**
  - [ ] Click "Delete" on a category with no blogs
  - [ ] Verify success message: "Blog category deleted."

---

### 1.4 Media Library Management

**Controller:** `app/Http/Controllers/Admin/MediaController.php`  
**Routes:** `/admin/media` (API)

- [ ] **List media:** Navigate to media library page, verify images display
- [ ] **Filter by folder:** Use folder filter, verify filtering works
- [ ] **Upload media:**
  - [ ] Click "Upload" button
  - [ ] Select image file (JPG, PNG, WebP)
  - [ ] Specify folder (e.g., "products")
  - [ ] Add title and alt text
  - [ ] Submit upload
  - [ ] Verify success response with media URL
  - [ ] Verify image appears in media library list
- [ ] **Delete media:**
  - [ ] Click "Delete" on uploaded test image
  - [ ] Confirm deletion
  - [ ] Verify image removed from storage
  - [ ] Verify image removed from database

---

### 1.5 Settings Management

**Controller:** `app/Http/Controllers/Admin/SettingsController.php`  
**Route:** `/admin/settings`

- [ ] **Navigate to settings:** Go to admin settings page
- [ ] **Switch tabs:** Verify tabs work (General, Payment, Shipping, Tax, Email)
- [ ] **Update general settings:**
  - [ ] Modify site name, description, or contact email
  - [ ] Submit form
  - [ ] Verify success message: "General settings saved."
  - [ ] Verify changes persist (reload page)
- [ ] **Update payment settings:**
  - [ ] Switch to Payment tab
  - [ ] Modify Razorpay keys or payment options
  - [ ] Submit form
  - [ ] Verify success message: "Payment settings saved."
- [ ] **Create custom setting:**
  - [ ] Click "Add Setting" (if available)
  - [ ] Fill in: Key, Value, Type, Group
  - [ ] Submit form
  - [ ] Verify success message: "Setting created."
- [ ] **Delete custom setting:**
  - [ ] Click "Delete" on test setting
  - [ ] Verify success message: "Setting deleted."

---

### 1.6 Subscription Management

**Controller:** `app/Http/Controllers/Admin/SubscriptionManagementController.php`  
**Route:** `/admin/subscriptions`

- [ ] **List subscriptions:** Navigate to subscriptions page, verify list with user info
- [ ] **Filter by status:** Use status dropdown (active/paused/cancelled), verify filtering
- [ ] **Search subscriptions:** Use search box (filters by user name), verify search works
- [ ] **View subscription details:**
  - [ ] Click "View" on a subscription
  - [ ] Verify subscription details page shows plan, user, status, billing dates
- [ ] **Update subscription status:**
  - [ ] Click "Edit" or change status dropdown
  - [ ] Change status from active to paused
  - [ ] Submit form
  - [ ] Verify success message: "Subscription updated."
  - [ ] **Verify email sent:** Check customer email inbox for "Subscription Paused" email
  - [ ] **Verify admin email sent:** Check admin email inbox for admin notification
  - [ ] Change status from paused to active (resumed)
  - [ ] **Verify email sent:** Check for "Subscription Resumed" email
  - [ ] Change status from active to cancelled
  - [ ] **Verify email sent:** Check for "Subscription Cancelled" email

---

### 1.7 Contact Messages Management

**Controller:** `app/Http/Controllers/Admin/ContactManagementController.php`  
**Route:** `/admin/contacts`

- [ ] **List contact messages:** Navigate to contacts page, verify messages display
- [ ] **Filter unread messages:** Click "Unread" filter, verify only unread messages show
- [ ] **Search contacts:** Use search box (searches name/email), verify search works
- [ ] **View contact message:**
  - [ ] Click "View" on an unread message
  - [ ] Verify message details display
  - [ ] Verify is_read flag set to true automatically
  - [ ] Verify unread count decrements
- [ ] **Delete contact message:**
  - [ ] Click "Delete" on a test message
  - [ ] Confirm deletion
  - [ ] Verify success message: "Message deleted."

---

### 1.8 Order Management

**Controller:** `app/Http/Controllers/Admin/OrderManagementController.php` (or similar)  
**Route:** `/admin/orders`

- [ ] **List orders:** Navigate to orders page, verify list with customer info and status
- [ ] **Filter orders:** Use status filter (pending, processing, shipped, delivered, cancelled)
- [ ] **View order details:**
  - [ ] Click "View" on an order
  - [ ] Verify order items, totals, shipping address display
  - [ ] Verify payment status and timeline entries
- [ ] **Update order status:**
  - [ ] Change status from "pending" to "processing"
  - [ ] Submit form
  - [ ] **Verify customer email sent:** Check customer email for "Order Status Updated" email
  - [ ] Verify timeline entry created with status change
  - [ ] Change status to "shipped"
  - [ ] **Verify email sent** again
  - [ ] Change status to "delivered"
  - [ ] **Verify email sent**
  - [ ] Try status "refunded"
  - [ ] **Verify email sent** with refund information

---

## Part 2: Customer Portal Verification

### 2.1 Authentication

**Controller:** `app/Http/Controllers/Web/CustomerAuthController.php`  
**Routes:** `/login`, `/register`, `/logout`

- [ ] **Login:**
  - [ ] Navigate to `/login`
  - [ ] Enter valid credentials (test customer account)
  - [ ] Submit form
  - [ ] Verify redirect to `/account`
  - [ ] Verify welcome message or user name displayed
- [ ] **Login with invalid credentials:**
  - [ ] Enter wrong password
  - [ ] Verify error message: "Invalid credentials."
  - [ ] Verify user NOT logged in
- [ ] **Register new account:**
  - [ ] Navigate to `/register`
  - [ ] Fill in: Name, Email, Password, Password Confirmation
  - [ ] Optionally add referral code
  - [ ] Submit form
  - [ ] Verify redirect to `/account`
  - [ ] Verify welcome message: "Welcome! Your account is ready."
  - [ ] Verify referral code generated for new user
- [ ] **Logout:**
  - [ ] Click "Logout" button
  - [ ] Verify redirect to home page
  - [ ] Verify session cleared (cannot access /account without login)

---

### 2.2 Account Dashboard

**Controller:** `app/Http/Controllers/Web/StorefrontController.php` (account method)  
**Route:** `/account`

- [ ] **Navigate to account:** Go to `/account` (requires login)
- [ ] **Verify stats cards display:**
  - [ ] Orders count
  - [ ] Active Subscriptions count
  - [ ] Referrals count
  - [ ] Reward Balance
- [ ] **Verify tabs display:**
  - [ ] Profile
  - [ ] Orders
  - [ ] Subscriptions
  - [ ] Referrals
  - [ ] Rewards
- [ ] **Switch tabs:** Click each tab, verify content changes

---

### 2.3 Profile Management

**Controller:** `app/Http/Controllers/Web/StorefrontController.php` (updateProfile method)  
**Route:** `/account` (Profile tab)

- [ ] **View profile:**
  - [ ] Navigate to Account → Profile tab
  - [ ] Verify form pre-filled with user data (name, phone, address, etc.)
- [ ] **Edit profile:**
  - [ ] Modify name, phone, address fields
  - [ ] Submit "Save Changes" button
  - [ ] Verify success message: "Profile updated." or similar
  - [ ] Reload page, verify changes persisted
- [ ] **Validation:**
  - [ ] Try submitting with empty required fields
  - [ ] Verify validation errors display

---

### 2.4 Password Change

**Controller:** `app/Http/Controllers/Web/StorefrontController.php` (changePassword method)  
**Route:** `/account` (Profile tab, password section)

- [ ] **Change password:**
  - [ ] Fill in: Current Password, New Password, Confirm New Password
  - [ ] Submit "Update Password" button
  - [ ] Verify success message: "Password changed successfully." or similar
- [ ] **Validation:**
  - [ ] Try wrong current password
  - [ ] Verify error: "Current password is incorrect."
  - [ ] Try mismatched confirmation
  - [ ] Verify error: "Passwords do not match."
- [ ] **Test new password:**
  - [ ] Logout
  - [ ] Login with new password
  - [ ] Verify successful login

---

### 2.5 Order History

**Controller:** `app/Http/Controllers/Web/StorefrontController.php` (account method)  
**Route:** `/account` (Orders tab)

- [ ] **View orders:**
  - [ ] Navigate to Account → Orders tab
  - [ ] Verify list of past orders displays
  - [ ] Verify order details: Order number, date, status, total
- [ ] **Filter/sort orders:** (if available)
  - [ ] Test status filter (pending, delivered, etc.)
  - [ ] Verify filtering works
- [ ] **View order details:**
  - [ ] Click on an order
  - [ ] Verify order items, shipping address, payment status
  - [ ] Verify timeline or tracking info (if available)

---

### 2.6 Subscriptions

**Controller:** `app/Http/Controllers/Web/StorefrontController.php` (account method)  
**Route:** `/account` (Subscriptions tab)

- [ ] **View subscriptions:**
  - [ ] Navigate to Account → Subscriptions tab
  - [ ] Verify list of subscriptions (active, paused, cancelled)
  - [ ] Verify subscription details: Plan name, frequency, next billing date, status
- [ ] **Pause subscription:** (if customer can pause)
  - [ ] Click "Pause" button
  - [ ] Confirm action
  - [ ] Verify status changes to "Paused"
  - [ ] **Verify email sent:** Check email for "Subscription Paused" notification
- [ ] **Resume subscription:** (if customer can resume)
  - [ ] Click "Resume" button on paused subscription
  - [ ] Verify status changes to "Active"
  - [ ] **Verify email sent:** Check email for "Subscription Resumed" notification
- [ ] **Cancel subscription:** (if customer can cancel)
  - [ ] Click "Cancel" button
  - [ ] Confirm action
  - [ ] Verify status changes to "Cancelled"
  - [ ] **Verify email sent:** Check email for "Subscription Cancelled" notification

---

### 2.7 Contact Form

**Controller:** `app/Http/Controllers/Web/StorefrontController.php` (contactSubmit method)  
**Route:** `/contact`

- [ ] **Navigate to contact page:** Go to `/contact`
- [ ] **Submit contact form:**
  - [ ] Fill in: Name, Email, Phone, Query Type, Message
  - [ ] Submit form
  - [ ] Verify success message: "Thank you for contacting us!" or similar
  - [ ] **Verify customer email sent:** Check customer email inbox for confirmation
  - [ ] **Verify admin email sent:** Check admin email for contact lead notification
- [ ] **Validation:**
  - [ ] Try submitting with empty required fields
  - [ ] Verify validation errors display
  - [ ] Try invalid email format
  - [ ] Verify email validation error

---

### 2.8 Shopping Flow

**Controller:** `app/Http/Controllers/Web/StorefrontController.php`  
**Routes:** `/products`, `/cart`, `/checkout`

- [ ] **Browse products:**
  - [ ] Navigate to `/products` or Shop page
  - [ ] Verify product grid displays with images, names, prices
  - [ ] Click on a product
  - [ ] Verify product detail page loads with full description, ingredients, nutrition info
- [ ] **Add to cart:**
  - [ ] Click "Add to Cart" button
  - [ ] Verify success message or cart badge updates
  - [ ] Navigate to `/cart`
  - [ ] Verify product appears in cart with correct quantity and price
- [ ] **Update cart:**
  - [ ] Increase quantity using + button
  - [ ] Verify subtotal updates
  - [ ] Decrease quantity using - button
  - [ ] Verify subtotal updates
  - [ ] Remove item from cart
  - [ ] Verify item removed
- [ ] **Proceed to checkout:**
  - [ ] Add 2-3 products to cart
  - [ ] Click "Checkout" button
  - [ ] Verify redirect to `/checkout` (requires login)
- [ ] **Checkout:**
  - [ ] Verify checkout form displays with shipping address fields
  - [ ] Fill in delivery address
  - [ ] Verify order summary shows items, subtotal, shipping, total
  - [ ] Click "Place Order" button
  - [ ] **Razorpay payment:**
    - [ ] Verify Razorpay modal opens
    - [ ] Complete payment (use test card: 4111 1111 1111 1111)
    - [ ] Verify redirect to order success page
  - [ ] **Verify order confirmation email sent**
  - [ ] **Verify admin order notification email sent**
  - [ ] Navigate to Account → Orders
  - [ ] Verify new order appears in order history

---

### 2.9 Wishlist

**Controller:** `app/Http/Controllers/Web/StorefrontController.php` (toggleWishlist method)  
**Route:** `/wishlist`

- [ ] **Add to wishlist:**
  - [ ] Navigate to a product page
  - [ ] Click "Add to Wishlist" or heart icon
  - [ ] Verify success message
- [ ] **View wishlist:**
  - [ ] Navigate to `/wishlist`
  - [ ] Verify saved products display
- [ ] **Remove from wishlist:**
  - [ ] Click "Remove" or heart icon again
  - [ ] Verify product removed from wishlist

---

### 2.10 Blog & Recipes

**Controller:** `app/Http/Controllers/Web/StorefrontController.php` (blogIndex, blogShow methods)  
**Routes:** `/blog`, `/blog/{slug}`

- [ ] **View blog listing:**
  - [ ] Navigate to `/blog`
  - [ ] Verify blog posts display with images, titles, excerpts
  - [ ] Verify only published posts show (no drafts)
- [ ] **View blog post:**
  - [ ] Click on a blog post
  - [ ] Verify full content displays
  - [ ] Verify meta title and description set (check page source)
- [ ] **View recipes page:**
  - [ ] Navigate to `/recipes` (if exists)
  - [ ] Verify recipe tips and featured articles display

---

## Part 3: Email Verification

### 3.1 Order Emails

- [ ] **Order Placed (Customer):**
  - [ ] Place a test order
  - [ ] Check customer email inbox
  - [ ] Verify email subject: "Order Confirmation - #{order_number}"
  - [ ] Verify email contains order items, total, shipping address
- [ ] **New Order (Admin):**
  - [ ] Check admin email inbox (<customercare@numnam.com> or configured recipient)
  - [ ] Verify email subject: "New Order - #{order_number}"
  - [ ] Verify email contains customer info and order details
- [ ] **Order Status Change (Customer):**
  - [ ] Change order status in admin panel (e.g., pending → processing)
  - [ ] Check customer email
  - [ ] Verify email subject includes new status
  - [ ] Verify email explains status change

---

### 3.2 Subscription Emails

- [ ] **Subscription Created (Customer):**
  - [ ] Create a new subscription via pricing page
  - [ ] Check customer email
  - [ ] Verify email subject: "Subscription Activated" or similar
  - [ ] Verify email contains plan details, frequency, next billing date
- [ ] **Subscription Created (Admin):**
  - [ ] Check admin email
  - [ ] Verify email subject includes customer name and subscription plan
- [ ] **Subscription Paused (Customer & Admin):**
  - [ ] Pause a subscription in admin panel or customer account
  - [ ] Verify both customer and admin receive "Subscription Paused" emails
- [ ] **Subscription Resumed (Customer & Admin):**
  - [ ] Resume a paused subscription
  - [ ] Verify both customer and admin receive "Subscription Resumed" emails
- [ ] **Subscription Cancelled (Customer & Admin):**
  - [ ] Cancel a subscription
  - [ ] Verify both customer and admin receive "Subscription Cancelled" emails
- [ ] **Billing Failure (Customer & Admin):**
  - [ ] Simulate billing failure (requires Razorpay test environment or manual trigger)
  - [ ] Verify customer receives email with retry count and failure reason
  - [ ] Verify admin receives billing failure notification
- [ ] **Auto-Cancellation (Customer & Admin):**
  - [ ] Simulate max billing retries reached
  - [ ] Verify customer receives "Subscription Cancelled" email with reason
  - [ ] Verify admin receives auto-cancellation notification

---

### 3.3 Contact Form Emails

- [ ] **Contact Form Submission (Customer Confirmation):**
  - [ ] Submit contact form
  - [ ] Check customer email
  - [ ] Verify confirmation email received
- [ ] **Contact Lead (Admin Notification):**
  - [ ] Check admin email
  - [ ] Verify admin receives contact lead notification with customer message

---

### 3.4 Password Reset Emails

- [ ] **Forgot Password (Customer):**
  - [ ] Navigate to `/forgot-password`
  - [ ] Enter email address
  - [ ] Submit form
  - [ ] Check customer email
  - [ ] Verify password reset link received
  - [ ] Click link, verify redirect to reset form
  - [ ] Enter new password
  - [ ] Submit form
  - [ ] Verify success message
  - [ ] Login with new password

---

## Part 4: Payment Integration Verification

### 4.1 Razorpay Test Mode

- [ ] **Configure test keys:**
  - [ ] Ensure `.env` has Razorpay test keys (not live keys)
  - [ ] Restart Laravel app to load new keys
- [ ] **Test payment flow:**
  - [ ] Add products to cart
  - [ ] Proceed to checkout
  - [ ] Place order
  - [ ] Verify Razorpay modal opens
  - [ ] Use test card: **4111 1111 1111 1111**, CVV: **123**, Expiry: any future date
  - [ ] Complete payment
  - [ ] Verify redirect to order success page
  - [ ] Verify order status is "paid" in database
  - [ ] Check Razorpay dashboard for test payment

---

### 4.2 Razorpay Live Mode (Production Only)

- [ ] **Configure live keys:**
  - [ ] Ensure `.env` has Razorpay live keys
  - [ ] Restart Laravel app
- [ ] **Test with small amount:**
  - [ ] Place order with real product (₹1 or ₹10 test order)
  - [ ] Use real payment method (UPI, card, netbanking)
  - [ ] Complete payment
  - [ ] Verify order success
  - [ ] Check Razorpay dashboard for live payment
  - [ ] **Refund test order** via Razorpay dashboard to complete test

---

## Part 5: Performance & Security Checks

### 5.1 Page Load Times

- [ ] **Test major pages:**
  - [ ] Home page loads in < 3 seconds
  - [ ] Product listing loads in < 3 seconds
  - [ ] Product detail loads in < 2 seconds
  - [ ] Cart loads in < 2 seconds
  - [ ] Checkout loads in < 3 seconds
  - [ ] Account dashboard loads in < 3 seconds
- [ ] **Optimize if slow:**
  - [ ] Enable caching (Redis, file cache)
  - [ ] Optimize database queries (use eager loading)
  - [ ] Compress images
  - [ ] Enable CDN for assets

---

### 5.2 Security Checks

- [ ] **HTTPS enabled:** Verify site loads with https:// (SSL certificate valid)
- [ ] **CSRF protection:** Verify all forms have `@csrf` token
- [ ] **SQL injection prevention:** Verify all queries use parameter binding (Eloquent/Query Builder)
- [ ] **XSS prevention:** Verify user inputs are escaped in Blade templates
- [ ] **Authentication required:** Verify protected routes redirect to login
- [ ] **Authorization checks:** Verify admin routes require admin role
- [ ] **Password hashing:** Verify passwords stored as bcrypt hashes (not plain text)
- [ ] **API rate limiting:** Verify API routes have throttle middleware
- [ ] **Sensitive data:** Verify `.env` file not publicly accessible

---

## Part 6: Final Checks

- [ ] **All forms validated:** Client-side and server-side validation working
- [ ] **Error handling:** 404 pages styled, 500 errors logged (not exposed to users)
- [ ] **Mobile responsive:** Test all pages on mobile device (portrait/landscape)
- [ ] **Browser compatibility:** Test on Chrome, Firefox, Safari, Edge
- [ ] **Queue workers running:** Verify `php artisan queue:work` running for email jobs
- [ ] **Scheduled tasks running:** Verify `php artisan schedule:run` in cron for subscriptions
- [ ] **Backup system:** Verify database backups configured (daily/weekly)
- [ ] **Monitoring:** Set up uptime monitoring (UptimeRobot, Pingdom, etc.)
- [ ] **Analytics:** Google Analytics or similar installed and tracking

---

## Sign-Off

Once all checks are complete:

- [ ] **Development Lead:** ______________________ Date: __________
- [ ] **QA Lead:** ______________________ Date: __________
- [ ] **Product Owner:** ______________________ Date: __________

**Production go-live approved:** ☐ Yes ☐ No (if no, list blockers below)

**Blockers:**

1. _________________________________________________________________
2. _________________________________________________________________
3. _________________________________________________________________

---

**Portal verification complete! 🎉**
