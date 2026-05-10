class ApiEndpoints {
  const ApiEndpoints._();

  // Auth
  static const login = '/mobile/auth/login';
  static const register = '/mobile/auth/register';
  static const forgotPassword = '/mobile/auth/forgot-password';
  static const resetPassword = '/mobile/auth/reset-password';
  static const me = '/mobile/auth/me';
  static const refreshToken = '/mobile/auth/refresh';
  static const avatar = '/mobile/auth/me/avatar';
  static const changePassword = '/mobile/auth/me/password';

  // Content
  static const homepage = '/mobile/homepage';
  static const products = '/mobile/products';
  static const pricingPlans = '/mobile/pricing-plans';
  static const blogs = '/mobile/blogs';
  static const menus = '/mobile/menus';

  // Cart
  static const cart = '/mobile/cart';

  // Orders
  static const orders = '/mobile/orders';

  // Wishlist
  static const wishlist = '/mobile/wishlist';

  // Reviews (use with product id)
  static String productReviews(int productId) =>
      '/mobile/products/$productId/reviews';

  // Subscriptions
  static const subscriptions = '/mobile/subscriptions';

  // Contact
  static const contactForm = '/mobile/contact-forms';
}
