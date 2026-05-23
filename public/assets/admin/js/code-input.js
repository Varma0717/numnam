/**
 * Code Input JavaScript
 * Handles validation and suggestions for tracking codes
 */

(function () {
    'use strict';

    // Common tracking code patterns
    const TRACKING_PATTERNS = {
        googlePixel: {
            name: 'Google Pixel',
            pattern: /<!-- Facebook Pixel Code -->|fbq\('init',/i,
            example: '<!-- Facebook Pixel Code -->\n<script>\n  !function(f,b,e,v,n,t,s)\n  {...}\n</script>'
        },
        googleAnalytics: {
            name: 'Google Analytics',
            pattern: /<!-- Global site tag|gtag|GA-|google-analytics/i,
            example: '<!-- Global site tag (gtag.js) - Google Analytics -->\n<script async src="https://www.googletagmanager.com/gtag/js?id=GA-YOUR-ID"></script>'
        },
        googleTagManager: {
            name: 'Google Tag Manager',
            pattern: /<!-- Google Tag Manager|googletagmanager\.com\/gtm\.js/i,
            example: '<!-- Google Tag Manager (noscript) -->\n<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-YOUR-ID"></iframe></noscript>'
        },
        facebookPixel: {
            name: 'Facebook Pixel',
            pattern: /facebook\.com\/en_US\/fbevents|fbq\('track'/i,
            example: '<script>\n!function(f,b,e,v,n,t,s){...}(window, document,\'script\',\'https://connect.facebook.net/en_US/fbevents.js\');\n</script>'
        }
    };

    /**
     * Initialize code input validation
     */
    document.addEventListener('DOMContentLoaded', function () {
        initializeCodeInputs();
    });

    /**
     * Initialize all code input fields
     */
    function initializeCodeInputs() {
        const codeInputs = document.querySelectorAll(
            'textarea[name*="tracking_"], textarea[name*="code"]'
        );

        codeInputs.forEach(input => {
            // Add validation event
            input.addEventListener('blur', validateCodeInput);
            input.addEventListener('change', validateCodeInput);

            // Add suggestion hints
            addSuggestionHint(input);
        });
    }

    /**
     * Validate code input
     */
    function validateCodeInput(e) {
        const input = e.currentTarget;
        const value = input.value.trim();
        const fieldName = input.getAttribute('name');

        // Remove existing validation message
        const existingMsg = input.closest('.admin-form-row')?.querySelector('[data-code-validation]');
        if (existingMsg) {
            existingMsg.remove();
        }

        if (!value) return; // No validation needed for empty fields

        const wrapper = input.closest('.admin-form-row');
        if (!wrapper) return;

        // Check for common issues
        const issues = [];

        // Issue 1: Check if code has script tags (required for most tracking codes)
        if (!/<script|<!--|<meta|<noscript/i.test(value)) {
            issues.push('Missing expected code structure (no <script>, <meta>, or <!-- tags)');
        }

        // Issue 2: Warn if code seems incomplete (ends abruptly)
        if (value.length < 50) {
            issues.push('Code seems very short - verify it\'s complete');
        }

        // Issue 3: Check for balanced tags
        const openTags = (value.match(/<[^>]+>/g) || []).length;
        if (openTags > 0 && openTags % 2 !== 0 && !value.includes('</')) {
            issues.push('Unbalanced HTML tags detected');
        }

        // Issue 4: Try to detect code type
        const detectedType = detectCodeType(value);
        if (detectedType) {
            const successMsg = document.createElement('div');
            successMsg.setAttribute('data-code-validation', 'detected');
            successMsg.style.cssText = 'margin-top: 8px; padding: 8px 10px; background: #ecfdf5; border-left: 3px solid var(--wp-success); border-radius: 4px; font-size: 13px; color: #15803d;';
            successMsg.innerHTML = `✓ <strong>${detectedType}</strong> code detected`;
            wrapper.appendChild(successMsg);
        }

        // Show issues if any
        if (issues.length > 0) {
            const warningMsg = document.createElement('div');
            warningMsg.setAttribute('data-code-validation', 'warning');
            warningMsg.style.cssText = 'margin-top: 8px; padding: 8px 10px; background: #fef3c7; border-left: 3px solid var(--wp-warning); border-radius: 4px; font-size: 13px; color: #92400e;';
            const issuesList = issues.map(issue => `• ${issue}`).join('<br>');
            warningMsg.innerHTML = `⚠ <strong>Verify:</strong><br>${issuesList}`;
            wrapper.appendChild(warningMsg);
        }
    }

    /**
     * Detect code type
     */
    function detectCodeType(code) {
        for (const [key, pattern] of Object.entries(TRACKING_PATTERNS)) {
            if (pattern.pattern.test(code)) {
                return pattern.name;
            }
        }
        return null;
    }

    /**
     * Add suggestion hint to code input
     */
    function addSuggestionHint(input) {
        const fieldName = input.getAttribute('name');
        const wrapper = input.closest('.admin-form-row');
        if (!wrapper) return;

        // Determine hint based on field name
        let hint = 'Paste your tracking code here';

        if (fieldName.includes('pixel')) {
            hint = 'Paste your Facebook Pixel code (usually starts with <!-- Facebook Pixel Code -->)';
        } else if (fieldName.includes('analytics')) {
            hint = 'Paste your Google Analytics code (usually starts with <!-- Global site tag -->)';
        } else if (fieldName.includes('tag_manager')) {
            hint = 'Paste your Google Tag Manager code';
        } else if (fieldName.includes('custom_head') || fieldName.includes('head_code')) {
            hint = 'Paste any additional code for the <head> tag (scripts, meta tags, etc.)';
        }

        const hintElement = wrapper.querySelector('.admin-field-desc');
        if (hintElement && !hintElement.textContent.includes(hint)) {
            // Hint already exists from Blade template, no need to add
        }
    }

    /**
     * Sanitize code input to prevent XSS (server should do this too)
     */
    window.sanitizeCodeInput = function (code) {
        // This is client-side only for UX feedback
        // Server-side sanitization is critical for security

        // Remove potentially dangerous patterns
        code = code.replace(/javascript:/gi, '');
        code = code.replace(/on\w+\s*=/gi, ''); // Remove event handlers

        return code;
    };

    /**
     * Validate code before form submission
     */
    document.addEventListener('submit', function (e) {
        const form = e.target;
        const codeInputs = form.querySelectorAll('textarea[name*="tracking_"]');

        codeInputs.forEach(input => {
            const value = input.value.trim();

            // Server will validate, but we can add client warnings
            if (value && value.length > 10000) {
                console.warn('Large tracking code detected:', input.name);
            }
        });
    });

    // Expose functions globally
    window.CodeInput = {
        validate: validateCodeInput,
        detectType: detectCodeType,
        sanitize: sanitizeCodeInput,
        initialize: initializeCodeInputs
    };
})();
