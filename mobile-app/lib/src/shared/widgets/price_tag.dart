import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../theme/colors.dart';

class PriceTag extends StatelessWidget {
  const PriceTag({
    super.key,
    required this.price,
    this.salePrice,
    this.fontSize = 16,
  });
  final double price;
  final double? salePrice;
  final double fontSize;

  bool get isOnSale => salePrice != null && salePrice! < price;

  @override
  Widget build(BuildContext context) {
    if (isOnSale) {
      return Row(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.end,
        children: [
          Text(
            '₹${salePrice!.toStringAsFixed(0)}',
            style: GoogleFonts.poppins(
              fontSize: fontSize,
              fontWeight: FontWeight.w700,
              color: kCoral,
            ),
          ),
          const SizedBox(width: 6),
          Text(
            '₹${price.toStringAsFixed(0)}',
            style: GoogleFonts.poppins(
              fontSize: fontSize * 0.75,
              fontWeight: FontWeight.w500,
              color: const Color(0xFF9E9EBE),
              decoration: TextDecoration.lineThrough,
            ),
          ),
        ],
      );
    }
    return Text(
      '₹${price.toStringAsFixed(0)}',
      style: GoogleFonts.poppins(
        fontSize: fontSize,
        fontWeight: FontWeight.w700,
        color: kNavy,
      ),
    );
  }
}
