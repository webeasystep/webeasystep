(function ($) {
    $.fn.i18n = function (options) {
        var settings = $.extend(
            {
                attribute: 'name',
            },
            options
        );

        var editors = new Map();  // Store references to editor instances

        this.each(function (index, obj) {
            var localesData = $(obj).attr('data-i18n');
            var locales = localesData ? localesData.split(',') : getBrowserLocales();
            var type = $(obj).attr('type');

            if (!locales.length || (type !== 'text' && !$(obj).is('textarea'))) {
                return;
            }
            var $parent = $('<div class="input-group-append"></div>');
            var attrVal = $(obj).attr(settings.attribute);
            var $inputs = $();
            var currentLanguageIndex = 0;

            for (i = 0; i < locales.length; i++) {
                var language = locales[i];
                var attrValue = attrVal ? attrVal + '_' + language : language;

                var $input = $(obj).clone().attr(settings.attribute, attrValue).css('display', 'none');
                if (i === 0) {
                    $input.css('display', 'block');
                }

                var dataValue = $(obj).attr('data-' + language);
                if (dataValue) {
                    $input.val(dataValue);
                }

                $inputs = $inputs.add($input);
                $parent.append('<button class="btn btn-outline-secondary lang-btn" type="button" data-lang="' + language + '">' + language.toUpperCase() + '</button>');
            }

            $(obj).after($parent);
            $(obj).parent().append($parent);
            $(obj).replaceWith($inputs);

            var $buttons = $parent.find('.btn');
            var width = $inputs.eq(0).css('width');

            $buttons.on('click', function (e) {
                var newIndex = $buttons.index(this);
                if (newIndex === currentLanguageIndex) {
                    return;
                }

                $inputs.eq(currentLanguageIndex).slideUp(300);
                $inputs.eq(newIndex).slideDown(300);

                currentLanguageIndex = newIndex;
            });

            $inputs.each(function (i, input) {
                var language = locales[i];

                // Initialize CKEditor on inputs with class 'ckeditor'
                if ($(input).hasClass('ckeditor')) {
                    var $wrapper = $('<div class="ckeditor-wrapper"></div>').attr('data-lang', language);
                    var $ckeditorTextarea = $('<textarea></textarea>').attr({
                        name: $(input).attr(settings.attribute),
                        class: 'form-control ckeditor',
                        'data-i18n': localesData,
                        'data-ar': $(input).attr('data-ar'),
                        'data-en': $(input).attr('data-en'),
                        style: $(input).attr('style'),
                        direction: language === 'ar' ? 'rtl' : 'ltr',
                        'text-align': language === 'ar' ? 'right' : 'left',
                        'display': 'none'
                    }).text($(input).val().trim());

                    $wrapper.append($ckeditorTextarea);
                    $(input).parent().append($wrapper);
                    $wrapper.after($parent);

                    // Remove the original CKEditor textarea
                    $(input).remove();

                    // Initialize CKEditor
                    ClassicEditor
                        .create($ckeditorTextarea.get(0), {
                            language: {
                                ui: language,
                                content: language
                            },
                            direction: language === 'ar' ? 'rtl' : 'ltr',
                            toolbar: {
                                items: [
                                    'heading', '|',
                                    'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                                    'bulletedList', 'numberedList', 'todoList', '|',
                                    'outdent', 'indent', '|',
                                    'undo', 'redo', '|',
                                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                                    'alignment', '|',
                                    'link', 'uploadImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                                    'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                                    'sourceEditing'
                                ],
                                shouldNotGroupWhenFull: true
                            },
                            // Enhanced Image Configuration
                            image: {
                                toolbar: [
                                    'imageTextAlternative',
                                    'toggleImageCaption',
                                    'imageStyle:inline',
                                    'imageStyle:block',
                                    'imageStyle:side',
                                    'linkImage',
                                    'resizeImage'
                                ],
                                styles: [
                                    'full',
                                    'side',
                                    'alignLeft',
                                    'alignCenter',
                                    'alignRight'
                                ],
                                resizeOptions: [
                                    {
                                        name: 'resizeImage:original',
                                        value: null,
                                        icon: 'original'
                                    },
                                    {
                                        name: 'resizeImage:50',
                                        value: '50',
                                        icon: 'medium'
                                    },
                                    {
                                        name: 'resizeImage:75',
                                        value: '75',
                                        icon: 'large'
                                    }
                                ]
                            },
                            // Code Block Configuration
                            codeBlock: {
                                languages: [
                                    { language: 'plaintext', label: 'Plain text' },
                                    { language: 'php', label: 'PHP' },
                                    { language: 'javascript', label: 'JavaScript' },
                                    { language: 'python', label: 'Python' },
                                    { language: 'html', label: 'HTML' },
                                    { language: 'css', label: 'CSS' },
                                    { language: 'sql', label: 'SQL' },
                                    { language: 'json', label: 'JSON' },
                                    { language: 'bash', label: 'Bash' }
                                ]
                            },
                            heading: {
                                options: [
                                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                                    { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                                    { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                                ]
                            },
                            removePlugins: [
                                // Disable plugins that are not needed or might conflict
                                // Collaboration features
                                'RealTimeCollaborativeComments',
                                'RealTimeCollaborativeTrackChanges',
                                'RealTimeCollaborativeRevisionHistory',
                                'PresenceList',
                                'Comments',
                                'TrackChanges',
                                'TrackChangesData',
                                'RevisionHistory',
                                'Pagination',
                                'WProofreader',
                                'MathType',
                                // The following plugins are part of the "Premium Features" and require a license key
                                'SlashCommand',
                                'Template',
                                'DocumentOutline',
                                'FormatPainter',
                                'TableOfContents',
                                'PasteFromOfficeEnhanced',
                                // AI Features
                                'AIAdapter',
                                'AIAssistant',
                                // Export features requiring license/config
                                'ExportPdf',
                                'ExportWord',
                                'ImportWord',
                                'MultiLevelList',
                                // Premium Plugins
                                'CaseChange',
                                'SimpleUploadAdapter' // Often redundant if not configured
                            ]
                        })
                        .then(editor => {
                            console.log(`Editor ${index + 1} initialized`);

                            // Store reference to this editor instance
                            editors.set($ckeditorTextarea.get(0), editor);

                            editor.model.document.on('change:data', () => {
                                const data = editor.getData();
                                $ckeditorTextarea.val(data); // Use html() instead of val()
                            });

                            // Set initial content from textarea to editor
                            editor.setData($ckeditorTextarea.val());

                            // Set the height of the editor
                            editor.editing.view.change(writer => {
                                writer.setStyle('height', '200px', editor.editing.view.document.getRoot());
                            });



                            // Show the first CKEditor wrapper initially
                            if (i !== 0) {
                                $wrapper.hide();
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }

                // Set the direction and alignment of the input based on the locale
                if (language === 'ar') {
                    $(input).css({ direction: 'rtl', 'text-align': 'right' });
                } else {
                    $(input).css({ direction: 'ltr', 'text-align': 'left' });
                }
            });
        });

        // Handle lang-btn clicks
        $(document).on('click', '.lang-btn', function () {
            const selectedLang = $(this).data('lang');
            console.log('Language button clicked:', $(this).data('lang'));

            // Get the closest .i18n-input parent of this button
            const $i18nInput = $(this).closest('.i18n-input');

            // Hide all CKEditor wrappers within this .i18n-input
            $i18nInput.find('.ckeditor-wrapper').slideUp(300);

            // Show the CKEditor wrapper for the selected language within this .i18n-input
            const $ckeditorWrapper = $i18nInput.find(`.ckeditor-wrapper[data-lang="${selectedLang}"]`);
            $ckeditorWrapper.slideDown(300);

            // Update CKEditor instance with the new content
            const textarea = $ckeditorWrapper.find('textarea').get(0);
            const editor = editors.get(textarea);
            console.log('Retrieved editor:', editor);

            if (editor) {
                editor.setData(textarea.value);
            }
        });

        return this;
    };

    function getBrowserLocales() {
        var navigatorLocale = navigator.language || navigator.userLanguage;
        return [navigatorLocale];
    }
})(jQuery);
