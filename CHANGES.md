# Changelog

All notable changes to the My Courses Block (curso_lista) plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.1] - 2024-12-28

### Added
- Enhanced input validation for configuration data
- Improved debugging controls with DEBUG_DEVELOPER checks
- Better HTML escaping for security compliance
- Context-aware string formatting for block titles

### Changed
- Optimized debug logging to only show in developer mode
- Improved error handling in cache operations
- Enhanced security validation for color inputs
- Better performance for configuration synchronization

### Fixed
- Resolved excessive logging in production environments
- Fixed potential XSS vulnerabilities in title display
- Improved cache clearing consistency
- Enhanced error handling for missing cache definitions

### Security
- Added proper input validation for all configuration fields
- Implemented secure HTML escaping for user inputs
- Enhanced context validation for string formatting
- Improved protection against malicious color values

## [2.0.0] - 2024-11-01

### Added
- Complete rewrite with modern Moodle architecture
- Advanced caching system for improved performance
- Gradient support for button styling
- Multi-language support (English, Spanish, Portuguese)
- Responsive design with mobile optimization
- ARIA accessibility features
- Progress visualization with SVG circles
- Instance-level configuration synchronization
- Comprehensive error handling and logging

### Changed
- Migrated from simple styling to advanced customization options
- Improved database queries with caching
- Enhanced user interface with modern design patterns
- Better code organization following Moodle standards

### Removed
- Legacy styling methods
- Deprecated configuration options
- Old cache implementations

## [1.0.0] - 2024-06-01

### Added
- Initial release of My Courses Block
- Basic course listing functionality
- Simple configuration options
- Multi-language support
- Basic styling customization
- Course progress display
- Direct course navigation

### Features
- Display enrolled courses for logged-in users
- Configurable button colors
- Course image display
- Progress indicators
- Responsive layout
- Cache implementation for performance

---

## Version Support

- **Version 2.0.x**: Supports Moodle 3.9+ (LTS)
- **Version 1.0.x**: Supports Moodle 3.5+ (deprecated)

## Migration Notes

### From 1.0.x to 2.0.x
- Automatic migration of existing configurations
- Enhanced features with backward compatibility
- No data loss during upgrade process
- New configuration options available after upgrade

## Security Notes

All versions include proper security measures:
- Input validation and sanitization
- XSS protection
- CSRF protection through Moodle's built-in mechanisms
- Secure database operations
- Proper user permission checks