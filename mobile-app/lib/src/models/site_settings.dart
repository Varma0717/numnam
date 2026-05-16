class SiteSettings {
  final bool codEnabled;
  final double codMinOrder;
  final double codMaxOrder;
  final List<String> codAllowedPincodes;

  const SiteSettings({
    this.codEnabled = false,
    this.codMinOrder = 0,
    this.codMaxOrder = 0,
    this.codAllowedPincodes = const [],
  });

  factory SiteSettings.fromJson(Map<String, dynamic> json) {
    final codPincodesStr =
        json['payment_cod_allowed_pincodes'] as String? ?? '';
    final codPincodes = codPincodesStr.isEmpty
        ? <String>[]
        : codPincodesStr
            .split(',')
            .map((e) => e.trim())
            .where((e) => e.isNotEmpty)
            .toList();

    return SiteSettings(
      codEnabled: json['payment_cod_enabled'] == '1',
      codMinOrder:
          double.tryParse(json['payment_cod_min_order']?.toString() ?? '0') ??
              0,
      codMaxOrder:
          double.tryParse(json['payment_cod_max_order']?.toString() ?? '0') ??
              0,
      codAllowedPincodes: codPincodes,
    );
  }

  bool canUseCOD({
    required double orderTotal,
    required String pincode,
  }) {
    if (!codEnabled) return false;
    if (codMinOrder > 0 && orderTotal < codMinOrder) return false;
    if (codMaxOrder > 0 && orderTotal > codMaxOrder) return false;
    if (codAllowedPincodes.isNotEmpty &&
        !codAllowedPincodes.contains(pincode.trim())) return false;
    return true;
  }
}
