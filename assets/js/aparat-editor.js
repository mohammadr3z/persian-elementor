/**
 * Aparat Video integration for Elementor Editor
 */
(function($) {
    'use strict';
    
    // Wait for Elementor editor to be ready
    elementor.hooks.addAction('panel/open_editor/widget/video', function(panel, model, view) {
        const videoTypeControl = panel.$el.find('[data-setting="video_type"]');
        
        // Watch for video type changes
        videoTypeControl.on('change', function() {
            setTimeout(function() {
                updateAparatPreview(model, view);
            }, 100);
        });
        
        // Watch for aparat URL changes
        panel.$el.find('[data-setting="aparat_url"]').on('input', function() {
            updateAparatPreview(model, view);
        });
        
        // Watch for other aparat settings changes
        panel.$el.find('[data-setting="start_m"], [data-setting="start_s"], [data-setting="mute_aparat"], [data-setting="title_show_aparat"], [data-setting="recom_self"]').on('change', function() { // Added recom_self
            updateAparatPreview(model, view);
        });
    });
    
    /**
     * Extract hash from Aparat URL
     */
    function extractAparatHash(url) {
        if (!url) return '';
        
        // Handle different URL formats
        const patterns = [
            /aparat\.com\/v\/([a-zA-Z0-9]+)/i,
            /videohash\/([a-zA-Z0-9]+)/i,
            /\/([a-zA-Z0-9]+)$/
        ];
        
        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match && match[1]) {
                return match[1];
            }
        }
        
        return '';
    }
    
    /**
     * Update the preview in the editor
     */
    function updateAparatPreview(model, view) {
        const settings = model.get('settings').attributes;
        
        // Only update for aparat video type
        if (settings.video_type !== 'aparat') {
            return;
        }
        
        // Get the video hash
        const videoUrl = settings.aparat_url || '';
        const videoHash = extractAparatHash(videoUrl);
        
        // Calculate start time
        const startM = parseInt(settings.start_m || 0);
        const startS = parseInt(settings.start_s || 0);
        const startTime = (startM * 60) + startS;
        
        // Build parameters
        const params = [];
        
        // Add parameters based on settings
        if (settings.title_show_aparat === 'yes') {
            params.push('titleShow=true');
        }
        
        if (settings.mute_aparat === 'yes') {
            params.push('muted=true');
        }
        
        if (settings.autoplay === 'yes') {
            params.push('autoplay=true');
        }
        
        if (startTime > 0) {
            params.push('t=' + startTime);
        }

        // Add recom parameter if enabled
        if (settings.recom_self === 'yes') {
            params.push('recom=self');
        }
        
        // Find the content wrapper
        const $widgetContent = view.$el.find('.elementor-widget-container .elementor-video-wrapper, .elementor-widget-container > .elementor-custom-embed'); // Adjusted selector for better compatibility
        
        // If no valid hash, show error
        if (!videoHash) {
            // Ensure the container exists before trying to set HTML
            if ($widgetContent.length === 0) {
                view.$el.find('.elementor-widget-container').html('<p>' + persianElementorAparat.invalidUrl + '</p>');
            } else {
                $widgetContent.html('<p>' + persianElementorAparat.invalidUrl + '</p>');
            }
            return;
        }
        
        // Build iframe URL
        let iframeSrc = `https://www.aparat.com/video/video/embed/videohash/${videoHash}/vt/frame`;
        if (params.length > 0) {
            iframeSrc += '?' + params.join('&');
        }
        
        // Prepare the iframe HTML
        const iframeHtml = `
            <style>
                .h_iframe-aparat_embed_frame {
                    position: relative;
                    overflow: hidden;
                    width: 100%;
                    padding-top: 57%; /* Aspect ratio 16:9 */
                }
                .h_iframe-aparat_embed_frame iframe {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    border: 0;
                }
            </style>
            <div class="h_iframe-aparat_embed_frame elementor-video-wrapper"> 
                <iframe src="${iframeSrc}" allow="autoplay" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>
            </div>
        `;

        // Update the widget content with our iframe
        // Ensure the container exists before trying to set HTML
        if ($widgetContent.length === 0) {
             // If the specific wrapper isn't found, fall back to the main container
             view.$el.find('.elementor-widget-container').html(iframeHtml);
        } else {
             $widgetContent.html(iframeHtml);
        }
    }
    
})(jQuery);
