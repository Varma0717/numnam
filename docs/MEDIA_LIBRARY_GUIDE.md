# Media Library - Quick Reference Guide

## 🚀 Quick Start

### Upload Files
1. Go to `/admin/media`
2. Drag files to the upload zone (or click to browse)
3. Files upload automatically

### Search Files
```
Type in search box → Press "Apply Filters"
```

### Filter by Folder
```
Select folder from dropdown → Press "Apply Filters"
```

---

## 📋 Common Tasks

### Upload Multiple Files at Once
```
1. Click upload zone OR drag multiple files
2. Set folder name (optional): "products", "banners", etc.
3. Set collection (optional): "featured", "gallery", etc.
4. Files upload with progress bar
```

### Find a Specific Image
```
Method 1: Search by name
- Type filename in search box
- Click "Apply Filters"

Method 2: Browse by folder
- Select folder from dropdown
- Click "Apply Filters"
```

### Edit Image Details
```
1. Click on any image in grid
2. Modal opens with image preview
3. Edit: Title, Alt Text, Caption, Folder
4. Click "Save Changes"
```

### Copy Image URL
```
1. Click on image to open preview
2. Find URL in details section
3. Click "Copy" button
4. URL copied to clipboard!
```

### Delete Single Image
```
1. Click on image
2. Scroll to bottom of modal
3. Click "Delete" button
4. Confirm deletion
```

### Delete Multiple Images
```
1. Click "Bulk Actions" button (top right)
2. Check boxes appear on images
3. Select images to delete
4. Click "Delete Selected"
5. Confirm deletion
```

---

## 🎯 Best Practices

### Folder Organization
Recommended folder structure:
```
products/      - Product images
banners/       - Homepage banners
blogs/         - Blog post images
icons/         - Icons and small graphics
general/       - Miscellaneous
```

### File Naming
✅ Good:
- `baby-food-organic.jpg`
- `banner-summer-sale.png`
- `blog-nutrition-tips.jpg`

❌ Avoid:
- `IMG_1234.jpg`
- `Screenshot 2024.png`
- `untitled.jpg`

### Alt Text
Always add descriptive alt text for accessibility:
```
✅ "Organic baby food jar with sweet potato and carrots"
❌ "Image 1" or leave empty
```

---

## 🔍 Filters & Sorting

### Available Filters
- **Search**: Filename, title, or alt text
- **Folder**: Specific folder
- **Type**: Images only, Documents only
- **Sort By**: Date (newest/oldest), Filename, Size

### Filter Combinations
You can combine multiple filters:
```
Folder = "products" 
+ Type = "image" 
+ Sort = "Date Added (Newest)"
= All product images, newest first
```

---

## 📊 Understanding the Grid

### Image Card Shows:
- ✅ Preview thumbnail
- ✅ Title (or filename)
- ✅ File size

### Hover Effect:
- Border highlights in blue
- Card lifts slightly
- Click to open full details

---

## 🎨 Keyboard Shortcuts

| Action | Shortcut |
|--------|----------|
| Close modal | ESC key |
| Select all (in bulk mode) | Click "Select All" |

---

## 🐛 Common Issues

### "Upload Failed"
**Fix:** Check file size (max 10MB) and format (JPG, PNG, GIF, WEBP, SVG, PDF)

### "Images Not Loading"
**Fix:** Run in terminal:
```bash
php artisan storage:link
```

### "Search Not Working"
**Fix:** Make sure to click "Apply Filters" after typing

### "Bulk Mode Not Showing"
**Fix:** Click the "Bulk Actions" button at top right

---

## 💡 Pro Tips

1. **Use Folders Wisely**
   - Organize by purpose: products, banners, blogs
   - Keep folder names lowercase and simple

2. **Fill in Metadata**
   - Add titles and alt text immediately after upload
   - Use descriptive names for better searchability

3. **Regular Cleanup**
   - Use bulk mode to delete unused images
   - Keep library organized

4. **Search Efficiently**
   - Search works on filename, title, AND alt text
   - Use specific keywords for faster results

5. **Copy URLs Easily**
   - Use the Copy button in preview modal
   - Paste directly into product/page image fields

---

## 📱 Mobile Usage

The media library is fully responsive:
- Grid adjusts to smaller screens
- Touch-friendly interface
- Swipe to close modal
- Optimized for tablets

---

## 🔐 Permissions

Only admin users can:
- Upload files
- Edit metadata
- Delete files
- Access media library

---

## 📞 Need Help?

**File Size Limits:**
- Images: Max 10MB
- PDFs: Max 10MB

**Supported Formats:**
- Images: JPG, JPEG, PNG, GIF, WEBP, SVG
- Documents: PDF

**Storage Location:**
```
storage/app/public/cms-media/[folder]/[filename]
```

**Public URL Format:**
```
https://yourdomain.com/storage/cms-media/[folder]/[filename]
```

---

## ✅ Quick Checklist

Before going live:
- [ ] All images have alt text
- [ ] Files organized in folders
- [ ] No duplicate images
- [ ] Tested on mobile device
- [ ] Storage linked (`php artisan storage:link`)

---

**Last Updated:** May 18, 2026
**Version:** 2.0 (WordPress-style upgrade)
