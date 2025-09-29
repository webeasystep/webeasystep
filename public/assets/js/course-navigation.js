/**
 * Course Navigation Helper
 * Provides utilities for maintaining last selected item state across course navigation
 */

/**
 * Get the last selected item for a specific course
 * @param {string} courseSlug - The course slug identifier
 * @returns {string|null} - The last selected item ID or null if not found
 */
function getCourseLastItem(courseSlug) {
    if (typeof(Storage) !== "undefined") {
        return localStorage.getItem(`course_${courseSlug}_last_item`);
    }
    return null;
}

/**
 * Construct course URL with last item parameter
 * @param {string} courseSlug - The course slug identifier  
 * @param {string} baseUrl - The base course URL
 * @returns {string} - Complete URL with last item parameter if available
 */
function getCourseUrlWithLastItem(courseSlug, baseUrl) {
    const lastItem = getCourseLastItem(courseSlug);
    if (lastItem) {
        const separator = baseUrl.includes('?') ? '&' : '?';
        return `${baseUrl}${separator}last_item=${lastItem}`;
    }
    return baseUrl;
}

/**
 * Navigate to course with last selected item
 * @param {string} courseSlug - The course slug identifier
 * @param {string} baseUrl - The base course URL
 */
function navigateToCourseWithLastItem(courseSlug, baseUrl) {
    const targetUrl = getCourseUrlWithLastItem(courseSlug, baseUrl);
    window.location.href = targetUrl;
}

/**
 * Update course navigation links on page load
 * Automatically enhances links with class 'course-nav-link' to include last item
 */
document.addEventListener('DOMContentLoaded', function() {
    const courseNavLinks = document.querySelectorAll('.course-nav-link');
    
    courseNavLinks.forEach(function(link) {
        const courseSlug = link.getAttribute('data-course-slug');
        const originalHref = link.getAttribute('href');
        
        if (courseSlug && originalHref) {
            // Update the href to include last item if available
            const enhancedUrl = getCourseUrlWithLastItem(courseSlug, originalHref);
            link.setAttribute('href', enhancedUrl);
            
            // Add click handler for dynamic updates
            link.addEventListener('click', function(e) {
                e.preventDefault();
                navigateToCourseWithLastItem(courseSlug, originalHref);
            });
        }
    });
});

// Make functions globally available
window.getCourseLastItem = getCourseLastItem;
window.getCourseUrlWithLastItem = getCourseUrlWithLastItem;
window.navigateToCourseWithLastItem = navigateToCourseWithLastItem;