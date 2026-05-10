class User {
  final int id;
  final String name;
  final String email;
  final String? phone;
  final String? dateOfBirth;
  final String? gender;
  final String? avatar;
  final String? addressLine1;
  final String? addressLine2;
  final String? city;
  final String? state;
  final String? postalCode;
  final String? country;
  final String? referralCode;

  const User({
    required this.id,
    required this.name,
    required this.email,
    this.phone,
    this.dateOfBirth,
    this.gender,
    this.avatar,
    this.addressLine1,
    this.addressLine2,
    this.city,
    this.state,
    this.postalCode,
    this.country,
    this.referralCode,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] as int,
      name: json['name'] as String? ?? '',
      email: json['email'] as String? ?? '',
      phone: json['phone'] as String?,
      dateOfBirth: json['date_of_birth'] as String?,
      gender: json['gender'] as String?,
      avatar: json['avatar'] as String?,
      addressLine1: json['address_line1'] as String?,
      addressLine2: json['address_line2'] as String?,
      city: json['city'] as String?,
      state: json['state'] as String?,
      postalCode: json['postal_code'] as String?,
      country: json['country'] as String?,
      referralCode: json['referral_code'] as String?,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'email': email,
        'phone': phone,
        'date_of_birth': dateOfBirth,
        'gender': gender,
        'avatar': avatar,
        'address_line1': addressLine1,
        'address_line2': addressLine2,
        'city': city,
        'state': state,
        'postal_code': postalCode,
        'country': country,
        'referral_code': referralCode,
      };
}
