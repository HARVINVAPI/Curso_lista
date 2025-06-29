# My Courses Block (curso_lista)

A modern and customizable Moodle block that displays enrolled courses with visual progress indicators and advanced styling options.

## Description

The **My Courses Block** provides an elegant way to display user-enrolled courses with the following features:

- 📚 **Visual Course Display**: Shows enrolled courses with course images and progress indicators
- 🎨 **Customizable Styling**: Support for solid colors and gradients for buttons
- 📊 **Progress Visualization**: SVG-based circular progress indicators (80px size)
- ⚡ **Performance Optimized**: Built-in caching system for improved performance
- 🔧 **Highly Configurable**: Instance-level configuration with real-time synchronization
- 🌐 **Multi-language Support**: Available in English, Spanish, and Portuguese
- ♿ **Accessibility**: ARIA-compliant with proper accessibility features
- 📱 **Responsive Design**: Works across all device sizes

## Features

### Core Functionality
- Displays all visible and active enrolled courses for the current user
- Shows course progress as circular progress indicators
- Provides direct links to courses with customizable button styling
- Handles cases with no enrolled courses gracefully

### Customization Options
- **Button Colors**: Choose between solid colors or gradients
- **Typography**: Customize title color, size, and weight
- **Layout**: Adjustable block height and padding options
- **Visual Effects**: Optional animations and hover effects

### Performance Features
- **Smart Caching**: 5-minute cache for course data and progress
- **Optimized Loading**: Static asset inclusion to prevent duplicates
- **Memory Efficient**: Minimal resource usage with intelligent cache management

## Requirements

- **Moodle Version**: 3.9 or higher (tested up to Moodle 4.x)
- **PHP Version**: 7.4 or higher
- **Database**: MySQL 5.7+ or PostgreSQL 10+

## Installation

### Method 1: Manual Installation
1. Download the plugin files
2. Extract to `/path/to/moodle/blocks/curso_lista/`
3. Visit Site Administration → Notifications to complete installation
4. Configure global settings if needed

### Method 2: Plugin Installer
1. Go to Site Administration → Plugins → Install plugins
2. Upload the plugin ZIP file
3. Follow the installation wizard

## Configuration

### Global Settings
Access via Site Administration → Plugins → Blocks → My Courses

- **Default Button Color**: Set default color for all instances
- **Cache Duration**: Configure cache time (default: 5 minutes)
- **Default Styling**: Set default visual options

### Instance Settings
Each block instance can be configured independently:

#### Button Styling
- **Color Type**: Choose between solid color or gradient
- **Solid Color**: Single color for buttons (hex or CSS names)
- **Gradient**: Start and end colors for gradient effect

#### Typography
- **Title Color**: Color for course titles
- **Title Size**: Font size (0.8rem to 1.3rem)
- **Title Weight**: Font weight (400 to 800)

#### Layout
- **Block Height**: Compact, normal, or large
- **Padding**: Small, normal, or large spacing

## Usage

### Adding the Block
1. Turn editing on in any supported page
2. Add block → My Courses
3. Configure the block settings as desired
4. Save changes

### Supported Page Types
- ✅ Site pages
- ✅ Course pages
- ✅ User dashboard (My Moodle)
- ✅ User profile pages
- ✅ All other page types

### User Experience
- Users see their enrolled courses automatically
- Progress is calculated from course completion
- Clicking on course buttons navigates directly to the course
- Visual feedback on hover and interaction

## Technical Details

### Caching System
- **Course Data**: Cached for 5 minutes per user
- **Progress Data**: Cached separately for optimal performance
- **Automatic Purging**: Cache cleared on configuration changes

### Accessibility Features
- ARIA labels for progress indicators
- Proper heading structure
- Keyboard navigation support
- Screen reader compatible

### Performance Optimizations
- Minimal database queries through caching
- Efficient CSS and JavaScript loading
- Optimized for large numbers of courses

## Troubleshooting

### Common Issues

**Block not showing courses**
- Verify user is enrolled in visible courses
- Check course start/end dates
- Clear cache if needed

**Styling not applying**
- Ensure valid color formats (hex: #FF0000)
- Check browser cache
- Verify CSS file permissions

**Performance issues**
- Reduce cache time if memory is limited
- Check database performance
- Review error logs for cache issues

### Debug Mode
Enable debugging in Moodle to see detailed error messages:
```php
// In config.php
$CFG->debug = E_ALL;
$CFG->debugdisplay = 1;
```

## Support

- **Version**: 2.0.1
- **Author**: Harvinvapi
- **License**: GNU GPL v3 or later
- **Support**: [GitHub Issues](https://github.com/your-repo/curso_lista/issues)

### Community
- 🌐 Website: [Educacion.lat](https://educacion.lat)
- 📷 Instagram: [@Harvinvapi](https://instagram.com/Harvinvapi)
- 💼 LinkedIn: [Harvinvapi](https://linkedin.com/in/harvinvapi)

## Contributing

Contributions are welcome! Please:
1. Fork the repository
2. Create a feature branch
3. Submit a pull request with clear description

## Changelog

### Version 2.0.1 (2024-12-28)
- Enhanced configuration synchronization
- Improved caching system
- Better error handling
- Performance optimizations
- Updated accessibility features

### Version 2.0.0
- Complete rewrite with modern architecture
- Added gradient support
- Implemented advanced caching
- Multi-language support
- Responsive design improvements

## License

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

---

**Developed with ❤️ by Harvinvapi**

*If this plugin has been useful, consider supporting its development with a small donation.*