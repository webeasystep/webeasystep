/**
 * Embedded Quiz Component for MSARLink Course View
 * Provides seamless quiz experience within the course interface
 */

class EmbeddedQuiz {
    constructor() {
        this.currentQuestionIndex = 0;
        this.questions = [];
        this.answers = {};
        this.timeLimit = 0;
        this.timeRemaining = 0;
        this.timerInterval = null;
        this.quizData = null;
        this.attemptId = null;
        this.isSubmitting = false;
        this.isMobile = this.detectMobile();
        this.isTablet = this.detectTablet();
        this.touchStartY = 0;
        this.touchEndY = 0;

        this.initializeEventListeners();
        this.initializeResponsiveFeatures();
    }

    /**
     * Detect if device is mobile
     */
    detectMobile() {
        return window.innerWidth <= 480 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    /**
     * Detect if device is tablet
     */
    detectTablet() {
        return window.innerWidth > 480 && window.innerWidth <= 768;
    }

    /**
     * Initialize responsive features
     */
    initializeResponsiveFeatures() {
        // Handle orientation changes
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                this.handleOrientationChange();
            }, 100);
        });

        // Handle window resize
        window.addEventListener('resize', this.debounce(() => {
            this.handleResize();
        }, 250));

        // Prevent zoom on double tap for iOS
        if (this.isMobile) {
            document.addEventListener('touchend', (e) => {
                const now = (new Date()).getTime();
                if (now - this.lastTouchEnd <= 300) {
                    e.preventDefault();
                }
                this.lastTouchEnd = now;
            }, false);
        }
    }

    /**
     * Handle orientation change
     */
    handleOrientationChange() {
        if ($('#embedded-quiz-modal').is(':visible')) {
            this.adjustModalForOrientation();
        }
    }

    /**
     * Handle window resize
     */
    handleResize() {
        this.isMobile = this.detectMobile();
        this.isTablet = this.detectTablet();
        
        if ($('#embedded-quiz-modal').is(':visible')) {
            this.adjustModalLayout();
        }
    }

    /**
     * Adjust modal for orientation changes
     */
    adjustModalForOrientation() {
        const modal = $('#embedded-quiz-modal .quiz-modal-container');
        
        if (window.orientation === 90 || window.orientation === -90) {
            // Landscape mode
            modal.addClass('landscape-mode');
        } else {
            // Portrait mode
            modal.removeClass('landscape-mode');
        }
    }

    /**
     * Adjust modal layout based on screen size
     */
    adjustModalLayout() {
        const modal = $('#embedded-quiz-modal .quiz-modal-container');
        
        if (this.isMobile) {
            modal.addClass('mobile-layout');
        } else {
            modal.removeClass('mobile-layout');
        }
        
        if (this.isTablet) {
            modal.addClass('tablet-layout');
        } else {
            modal.removeClass('tablet-layout');
        }
    }

    /**
     * Debounce function for performance
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Initialize event listeners for quiz interactions
     */
    initializeEventListeners() {
        // Close modal events
        $(document).on('click', '.quiz-modal-close, .quiz-modal-backdrop', (e) => {
            if (e.target === e.currentTarget) {
                this.closeQuiz();
            }
        });

        // Navigation events
        $(document).on('click', '.quiz-nav-prev', () => this.previousQuestion());
        $(document).on('click', '.quiz-nav-next', () => this.nextQuestion());
        $(document).on('click', '.quiz-submit-btn', () => this.submitQuiz());

        // Question navigation
        $(document).on('click', '.quiz-question-nav-btn', (e) => {
            const questionIndex = parseInt($(e.target).data('question')) - 1;
            this.goToQuestion(questionIndex);
        });

        // Answer selection events
        $(document).on('change', '.quiz-answer-input', (e) => {
            this.saveAnswer(e);
        });

        // Touch events for mobile swipe navigation
        if (this.isMobile || this.isTablet) {
            $(document).on('touchstart', '.quiz-question', (e) => {
                this.handleTouchStart(e);
            });

            $(document).on('touchend', '.quiz-question', (e) => {
                this.handleTouchEnd(e);
            });
        }

        // Keyboard navigation
        $(document).on('keydown', (e) => {
            if ($('#embedded-quiz-modal').is(':visible')) {
                if (e.key === 'ArrowLeft') this.previousQuestion();
                if (e.key === 'ArrowRight') this.nextQuestion();
                if (e.key === 'Escape') this.closeQuiz();
            }
        });

        // Prevent body scroll when modal is open on mobile
        $(document).on('touchmove', (e) => {
            if ($('#embedded-quiz-modal').is(':visible') && this.isMobile) {
                if (!$(e.target).closest('.quiz-modal-body').length) {
                    e.preventDefault();
                }
            }
        });
    }

    /**
     * Handle touch start for swipe navigation
     */
    handleTouchStart(e) {
        this.touchStartY = e.originalEvent.touches[0].clientY;
        this.touchStartX = e.originalEvent.touches[0].clientX;
    }

    /**
     * Handle touch end for swipe navigation
     */
    handleTouchEnd(e) {
        if (!this.touchStartY || !this.touchStartX) return;

        this.touchEndY = e.originalEvent.changedTouches[0].clientY;
        this.touchEndX = e.originalEvent.changedTouches[0].clientX;

        const deltaX = this.touchStartX - this.touchEndX;
        const deltaY = this.touchStartY - this.touchEndY;

        // Only handle horizontal swipes (ignore vertical scrolling)
        if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > 50) {
            if (deltaX > 0) {
                // Swipe left - next question
                this.nextQuestion();
            } else {
                // Swipe right - previous question
                this.previousQuestion();
            }
        }

        // Reset touch coordinates
        this.touchStartY = 0;
        this.touchStartX = 0;
        this.touchEndY = 0;
        this.touchEndX = 0;
    }

    /**
     * Start the embedded quiz
     * @param {number} quizId - Quiz ID
     * @param {string} courseSlug - Course slug (optional)
     * @param {number} itemId - Item ID (optional)
     * @returns {Promise} Promise that resolves when quiz starts or rejects on error
     */
    async startQuiz(quizId, courseSlug = null, itemId = null) {
        console.log('COURSE_VIEW JS DEBUG - Starting quiz:', { quizId, courseSlug, itemId });

        try {
            // Build the URL for starting the embedded quiz
            let url = `/quizzes/start-embedded/${quizId}`;

            console.log('COURSE_VIEW JS DEBUG - Quiz URL:', url);

            // Make the request to start the quiz
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    course_slug: courseSlug,
                    item_id: itemId
                })
            });

            console.log('COURSE_VIEW JS DEBUG - Response status:', response.status);

            if (!response.ok) {
                // Get the error response data for better error messages
                let errorData;
                try {
                    errorData = await response.json();
                } catch (e) {
                    errorData = { message: `HTTP ${response.status}: ${response.statusText}` };
                }

                // Handle specific error cases with user-friendly messages
                if (response.status === 403) {
                    this.showErrorModal(errorData.message || 'تم استنفاد المحاولات المسموحة لهذا الكويز');
                    return; // Don't throw, just show the modal and return
                } else if (response.status === 401) {
                    this.showErrorModal('يجب تسجيل الدخول أولاً لأخذ الكويز');
                    return;
                } else if (response.status === 404) {
                    this.showErrorModal('الكويز غير موجود أو غير متاح');
                    return;
                } else {
                    this.showErrorModal(errorData.message || 'حدث خطأ أثناء تحميل الكويز. يرجى المحاولة مرة أخرى.');
                    return;
                }
            }

            const data = await response.json();
            console.log('COURSE_VIEW JS DEBUG - Response data:', data);

            if (data.success) {
                // Store quiz data
                this.quiz = data.quiz;
                this.questions = data.questions;
                this.attemptId = data.attempt_id;
                this.currentQuestionIndex = 0;
                this.userAnswers = {};

                // Initialize timer
                this.timeLimit = parseInt(data.quiz.time_limit_minutes) * 60;
                this.timeRemaining = this.timeLimit;

                // Build and show the quiz modal
                this.buildQuizModal();
                this.showQuizModal();
                this.startTimer();
            } else {
                throw new Error(data.message || 'Failed to start quiz');
            }

        } catch (error) {
            console.error('Error starting quiz:', error);
            // Only show error modal if we haven't already handled the error above
            if (error.message && !error.message.includes('HTTP')) {
                this.showErrorModal(error.message);
            }
            // Don't re-throw the error to prevent console errors
        }
    }

    /**
     * Build the quiz modal HTML structure
     */
    buildQuizModal() {
        const modalHtml = `
            <div id="embedded-quiz-modal" class="quiz-modal-overlay">
                <div class="quiz-modal-container ${this.isMobile ? 'mobile-layout' : ''} ${this.isTablet ? 'tablet-layout' : ''}">
                    <!-- Quiz Header -->
                    <div class="quiz-modal-header">
                        <div class="quiz-header-left">
                            <h3 class="quiz-title">${this.escapeHtml(this.quiz.quiz_title)}</h3>
                            ${!this.isMobile ? `<p class="quiz-description">${this.escapeHtml(this.quiz.quiz_desc || '')}</p>` : ''}
                        </div>
                        <div class="quiz-header-right">
                            <div class="quiz-timer">
                                <i class="fas fa-clock"></i>
                                <span id="quiz-timer-display">${this.formatTime(this.timeRemaining)}</span>
                            </div>
                            <div class="quiz-progress-info">
                                <span class="quiz-question-counter">
                                    ${this.isMobile ? '' : 'Question '}
                                    <span id="current-question-num">1</span> ${this.isMobile ? '/' : 'of'} ${this.questions.length}
                                </span>
                            </div>
                            <button class="quiz-modal-close" title="Close Quiz">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Quiz Progress Bar -->
                    <div class="quiz-progress-bar">
                        <div class="quiz-progress-fill" id="quiz-progress-fill" style="width: ${(1/this.questions.length)*100}%"></div>
                    </div>

                    <!-- Quiz Content -->
                    <div class="quiz-modal-body">
                        <div id="quiz-questions-container">
                            ${this.buildQuestionsHtml()}
                        </div>
                        
                        ${this.isMobile ? this.buildMobileSwipeHint() : ''}
                    </div>

                    <!-- Quiz Navigation -->
                    <div class="quiz-modal-footer">
                        <div class="quiz-nav-left">
                            <button class="btn btn-secondary quiz-nav-prev" style="display: none;">
                                <i class="fas fa-chevron-left"></i> ${this.isMobile ? 'السابق' : 'Previous Question'}
                            </button>
                        </div>
                        
                        ${!this.isMobile ? `<div class="quiz-nav-center">
                            <div class="quiz-question-navigation">
                                ${this.buildQuestionNavigation()}
                            </div>
                        </div>` : ''}
                        
                        <div class="quiz-nav-right">
                            <button class="btn btn-primary quiz-nav-next">
                                ${this.isMobile ? 'التالي' : 'Next Question'} <i class="fas fa-chevron-right"></i>
                            </button>
                            <button class="btn btn-success quiz-submit-btn" style="display: none;">
                                <i class="fas fa-check"></i> ${this.isMobile ? 'إنهاء' : 'Finish Quiz'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal and add new one
        $('#embedded-quiz-modal').remove();
        $('body').append(modalHtml);
        
        // Apply responsive adjustments
        this.adjustModalLayout();
    }

    /**
     * Build mobile swipe hint
     */
    buildMobileSwipeHint() {
        return `
            <div class="mobile-swipe-hint" style="text-align: center; padding: 10px; color: #666; font-size: 0.8rem;">
                <i class="fas fa-hand-point-left"></i> اسحب يميناً أو يساراً للتنقل بين الأسئلة
            </div>
        `;
    }

    /**
     * Show the quiz modal with responsive adjustments
     */
    showQuizModal() {
        $('#embedded-quiz-modal').fadeIn(300);
        $('body').addClass('quiz-modal-open');
        
        // Prevent body scroll on mobile
        if (this.isMobile) {
            $('body').css('overflow', 'hidden');
        }
        
        this.updateNavigationButtons();
        this.updateQuestionNavigation();
        
        // Focus management for accessibility
        setTimeout(() => {
            $('#embedded-quiz-modal .quiz-modal-container').focus();
        }, 350);
    }

    /**
     * Close the quiz modal with responsive cleanup
     */
    closeQuiz() {
        if (this.isSubmitting) return;

        const confirmMessage = this.isMobile ? 
            'هل أنت متأكد من إغلاق الاختبار؟ ستفقد إجاباتك.' : 
            'Are you sure you want to close the quiz? Your answers will be lost.';

        if (confirm(confirmMessage)) {
            this.stopTimer();
            $('#embedded-quiz-modal').fadeOut(300, () => {
                $('#embedded-quiz-modal').remove();
            });
            $('body').removeClass('quiz-modal-open');
            
            // Restore body scroll
            if (this.isMobile) {
                $('body').css('overflow', '');
            }
        }
    }

    /**
     * Enhanced navigation with haptic feedback on mobile
     */
    previousQuestion() {
        if (this.currentQuestionIndex > 0) {
            this.currentQuestionIndex--;
            this.showCurrentQuestion();
            this.triggerHapticFeedback();
        }
    }

    /**
     * Enhanced navigation with haptic feedback on mobile
     */
    nextQuestion() {
        if (this.currentQuestionIndex < this.questions.length - 1) {
            this.currentQuestionIndex++;
            this.showCurrentQuestion();
            this.triggerHapticFeedback();
        }
    }

    /**
     * Trigger haptic feedback on supported devices
     */
    triggerHapticFeedback() {
        if (navigator.vibrate && this.isMobile) {
            navigator.vibrate(50); // Short vibration
        }
    }

    /**
     * Enhanced question display with smooth transitions
     */
    showCurrentQuestion() {
        // Hide all questions with fade effect
        $('.quiz-question').removeClass('active').fadeOut(150);
        
        // Show current question with fade effect
        setTimeout(() => {
            $(`.quiz-question[data-question-index="${this.currentQuestionIndex}"]`)
                .addClass('active')
                .fadeIn(150);
            
            this.updateNavigationButtons();
            this.updateQuestionNavigation();
            this.updateProgressBar();
            
            // Scroll to top of question on mobile
            if (this.isMobile) {
                $('.quiz-modal-body').scrollTop(0);
            }
        }, 150);
    }

    /**
     * Update progress bar with smooth animation
     */
    updateProgressBar() {
        const progress = ((this.currentQuestionIndex + 1) / this.questions.length) * 100;
        $('#quiz-progress-fill').css('width', `${progress}%`);
        $('#current-question-num').text(this.currentQuestionIndex + 1);
    }

    /**
     * Enhanced error modal for mobile devices
     */
    showErrorModal(message, isMaxAttempts = false) {
        const modalClass = isMaxAttempts ? 'max-attempts-error' : 'error';
        const iconClass = isMaxAttempts ? 'fas fa-exclamation-triangle' : 'fas fa-exclamation-circle';
        const title = isMaxAttempts ? 'تم استنفاد المحاولات' : 'خطأ في تحميل الاختبار';
        
        const errorModalHtml = `
            <div class="quiz-modal-overlay show">
                <div class="quiz-modal-container ${modalClass}">
                    <div class="error-content">
                        <div class="error-icon">
                            <i class="${iconClass}"></i>
                        </div>
                        <h4 class="error-title">${title}</h4>
                        <div class="error-message">
                            <p class="lead">${message}</p>
                        </div>
                        <div class="error-actions">
                            <button class="btn btn-primary" onclick="location.reload()">
                                <i class="fas fa-redo"></i> ${this.isMobile ? 'إعادة تحميل' : 'إعادة تحميل الصفحة'}
                            </button>
                            <button class="btn btn-secondary" onclick="$('.quiz-modal-overlay').remove()">
                                <i class="fas fa-times"></i> ${this.isMobile ? 'إغلاق' : 'إغلاق'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('body').append(errorModalHtml);
    }

    /**
     * Build HTML for all questions
     */
    buildQuestionsHtml() {
        return this.questions.map((question, index) => {
            const isActive = index === 0 ? 'active' : '';
            return `
                <div class="quiz-question ${isActive}" data-question-index="${index}">
                    <div class="question-header">
                        <h4 class="question-title">
                            Question ${index + 1}
                            <span class="question-points">${question.points || 1} Points</span>
                        </h4>
                    </div>
                    <div class="question-text">
                        <p>${this.escapeHtml(question.question_text)}</p>
                    </div>
                    <div class="question-options">
                        ${this.buildQuestionOptions(question, index)}
                    </div>
                </div>
            `;
        }).join('');
    }

    /**
     * Build options for a specific question
     */
    buildQuestionOptions(question, questionIndex) {
        const rawOptions = question.options;
        const options = Array.isArray(rawOptions)
            ? rawOptions
            : (typeof rawOptions === 'string' ? JSON.parse(rawOptions) : null);

        switch (question.question_type) {
            case 'single_choice':
                if (!options) return '<p class="text-muted text-center py-3">⚠️ لا توجد خيارات لهذا السؤال</p>';
                return options.map((option, optIndex) => `
                    <div class="quiz-option">
                        <label class="quiz-option-label">
                            <input type="radio" 
                                   name="answer_${questionIndex}" 
                                   value="${optIndex}"
                                   class="quiz-answer-input"
                                   data-question="${questionIndex}">
                            <span class="quiz-option-text">${this.escapeHtml(option)}</span>
                        </label>
                    </div>
                `).join('');

            case 'multiple_choice':
                if (!options) return '<p class="text-muted text-center py-3">⚠️ لا توجد خيارات لهذا السؤال</p>';
                return options.map((option, optIndex) => `
                    <div class="quiz-option">
                        <label class="quiz-option-label">
                            <input type="checkbox" 
                                   name="answer_${questionIndex}[]" 
                                   value="${optIndex}"
                                   class="quiz-answer-input"
                                   data-question="${questionIndex}">
                            <span class="quiz-option-text">${this.escapeHtml(option)}</span>
                        </label>
                    </div>
                `).join('');

            case 'true_false':
                return `
                    <div class="quiz-option">
                        <label class="quiz-option-label">
                            <input type="radio" 
                                   name="answer_${questionIndex}" 
                                   value="true"
                                   class="quiz-answer-input"
                                   data-question="${questionIndex}">
                            <span class="quiz-option-text">
                                <i class="fas fa-check text-success"></i> صحيح
                            </span>
                        </label>
                    </div>
                    <div class="quiz-option">
                        <label class="quiz-option-label">
                            <input type="radio" 
                                   name="answer_${questionIndex}" 
                                   value="false"
                                   class="quiz-answer-input"
                                   data-question="${questionIndex}">
                            <span class="quiz-option-text">
                                <i class="fas fa-times text-danger"></i> خطأ
                            </span>
                        </label>
                    </div>
                `;

            case 'fill_in_blank':
                return `
                    <div class="quiz-option" style="display:block;padding:0;">
                        <label style="display:block;font-size:0.88rem;color:#64748b;margin-bottom:6px;">اكتب إجابتك:</label>
                        <input type="text"
                               class="form-control quiz-answer-input"
                               name="answer_${questionIndex}"
                               data-question="${questionIndex}"
                               autocomplete="off"
                               dir="rtl"
                               placeholder="..."
                               style="text-align:right;border-radius:8px;padding:0.75rem 1rem;">
                    </div>
                `;

            case 'essay':
                return `
                    <div class="quiz-option" style="display:block;padding:0;">
                        <textarea class="form-control quiz-answer-input" 
                                  name="answer_${questionIndex}"
                                  data-question="${questionIndex}"
                                  rows="5"
                                  dir="rtl"
                                  placeholder="اكتب إجابتك هنا..."
                                  style="text-align:right;border-radius:8px;resize:vertical;"></textarea>
                        ${question.max_words ? `<small class="text-muted" style="display:block;margin-top:4px;">الحد الأقصى: ${question.max_words} كلمة</small>` : ''}
                    </div>
                `;

            default:
                return `<p class="text-muted text-center py-3">نوع السؤال غير مدعوم: ${question.question_type}</p>`;
        }
    }


    /**
     * Build question navigation buttons
     */
    buildQuestionNavigation() {
        return this.questions.map((_, index) => `
            <button class="quiz-question-nav-btn" 
                    data-question="${index + 1}"
                    title="السؤال ${index + 1}">
                ${index + 1}
            </button>
        `).join('');
    }

    /**
     * Show the quiz modal
     */
    showQuizModal() {
        $('#embedded-quiz-modal').fadeIn(300);
        $('body').addClass('quiz-modal-open');
        this.updateNavigationButtons();
        this.updateQuestionNavigation();
    }

    /**
     * Close the quiz modal
     */
    closeQuiz() {
        if (this.isSubmitting) return;

        if (confirm('Are you sure you want to close the quiz? Your answers will be lost.')) {
            this.stopTimer();
            $('#embedded-quiz-modal').fadeOut(300, () => {
                $('#embedded-quiz-modal').remove();
            });
            $('body').removeClass('quiz-modal-open');
        }
    }

    /**
     * Navigate to previous question
     */
    previousQuestion() {
        if (this.currentQuestionIndex > 0) {
            this.currentQuestionIndex--;
            this.showCurrentQuestion();
        }
    }

    /**
     * Navigate to next question
     */
    nextQuestion() {
        if (this.currentQuestionIndex < this.questions.length - 1) {
            this.currentQuestionIndex++;
            this.showCurrentQuestion();
        }
    }

    /**
     * Go to specific question
     */
    goToQuestion(questionIndex) {
        if (questionIndex >= 0 && questionIndex < this.questions.length) {
            this.currentQuestionIndex = questionIndex;
            this.showCurrentQuestion();
        }
    }

    /**
     * Show current question and update UI
     */
    showCurrentQuestion() {
        // Hide all questions
        $('.quiz-question').removeClass('active');

        // Show current question
        $(`.quiz-question[data-question-index="${this.currentQuestionIndex}"]`).addClass('active');

        // Update progress
        const progress = ((this.currentQuestionIndex + 1) / this.questions.length) * 100;
        $('#quiz-progress-fill').css('width', progress + '%');
        $('#current-question-num').text(this.currentQuestionIndex + 1);

        // Update navigation
        this.updateNavigationButtons();
        this.updateQuestionNavigation();
    }

    /**
     * Update navigation button states
     */
    updateNavigationButtons() {
        const isFirst = this.currentQuestionIndex === 0;
        const isLast = this.currentQuestionIndex === this.questions.length - 1;

        $('.quiz-nav-prev').toggle(!isFirst);
        $('.quiz-nav-next').toggle(!isLast);
        $('.quiz-submit-btn').toggle(isLast);
    }

    /**
     * Update question navigation indicators
     */
    updateQuestionNavigation() {
        $('.quiz-question-nav-btn').removeClass('active answered');

        // Mark current question as active
        $(`.quiz-question-nav-btn[data-question="${this.currentQuestionIndex + 1}"]`).addClass('active');

        // Mark answered questions
        Object.keys(this.answers).forEach(questionIndex => {
            $(`.quiz-question-nav-btn[data-question="${parseInt(questionIndex) + 1}"]`).addClass('answered');
        });
    }

    /**
     * Save answer for current question
     */
    saveAnswer(event) {
        const questionIndex = parseInt($(event.target).data('question'));
        const questionType = this.questions[questionIndex].question_type;

        if (questionType === 'multiple_choice') {
            // Handle multiple choice
            const checkedValues = [];
            $(`input[name="answer_${questionIndex}[]"]:checked`).each(function() {
                checkedValues.push($(this).val());
            });
            this.answers[questionIndex] = checkedValues;
        } else {
            // Handle single choice, true/false, essay
            this.answers[questionIndex] = $(event.target).val();
        }

        // Update question navigation
        this.updateQuestionNavigation();

        // Auto-save answer to server
        this.autoSaveAnswer(questionIndex);
    }

    /**
     * Auto-save answer to server
     */
    async autoSaveAnswer(questionIndex) {
        try {
            await fetch(`/quizzes/save-answer/${this.attemptId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    question_index: questionIndex,
                    answer: this.answers[questionIndex]
                })
            });
        } catch (error) {
            console.error('Error auto-saving answer:', error);
        }
    }

    /**
     * Submit the quiz
     * @param {boolean} isTimerExpired - Whether the quiz is being submitted due to timer expiration
     */
    async submitQuiz(isTimerExpired = false) {
        if (this.isSubmitting) return;

        // Only show confirmation for unanswered questions if not timer expired
        if (!isTimerExpired) {
            const unansweredCount = this.questions.length - Object.keys(this.answers).length;
            if (unansweredCount > 0) {
                if (!confirm(`لديك ${unansweredCount} أسئلة لم تجب عليها. هل تريد إنهاء الكويز؟`)) {
                    return;
                }
            }
        }

        this.isSubmitting = true;
        this.stopTimer();

        try {
            // Show submitting state
            $('.quiz-submit-btn').html('<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...').prop('disabled', true);

            const response = await fetch(`/quizzes/submit-embedded/${this.attemptId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    answers: this.answers,
                    completion_time: this.timeLimit - this.timeRemaining
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to submit quiz');
            }

            // Show results and close quiz
            this.showQuizResults(data);

            // Auto-close after showing results
            setTimeout(() => {
                this.closeQuizAfterCompletion(data);
            }, 3000);

        } catch (error) {
            console.error('Error submitting quiz:', error);
            alert('حدث خطأ أثناء إرسال الكويز. يرجى المحاولة مرة أخرى.');
            this.isSubmitting = false;
            $('.quiz-submit-btn').html('<i class="fas fa-check"></i> إنهاء الكويز').prop('disabled', false);
        }
    }

    /**
     * Show quiz results
     */
    showQuizResults(results) {
        const passed = results.score >= results.passing_score;
        const resultHtml = `
            <div class="quiz-results">
                <div class="result-icon ${passed ? 'success' : 'failure'}">
                    <i class="fas ${passed ? 'fa-check-circle' : 'fa-times-circle'}"></i>
                </div>
                <h3>${passed ? 'Congratulations! You Passed' : 'Sorry, You Did Not Pass'}</h3>
                <div class="result-score">
                    <span class="score">${results.score}%</span>
                    <span class="passing-score">Passing Score: ${results.passing_score}%</span>
                </div>
                <div class="result-details">
                    <div class="detail-item">
                        <span class="label">Correct Answers:</span>
                        <span class="value">${results.correct_answers}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Total Questions:</span>
                        <span class="value">${results.total_questions}</span>
                    </div>
                </div>
            </div>
        `;

        $('.quiz-modal-body').html(resultHtml);
        $('.quiz-modal-footer').hide();
    }

    /**
     * Close quiz after completion and navigate to next item
     */
    closeQuizAfterCompletion(results) {
        $('#embedded-quiz-modal').fadeOut(300, () => {
            $('#embedded-quiz-modal').remove();
            $('body').removeClass('quiz-modal-open');

            // Mark quiz item as complete and navigate to next
            if (results.next_item_url) {
                window.location.href = results.next_item_url;
            } else {
                // Reload current page to update progress
                window.location.reload();
            }
        });
    }

    /**
     * Start the quiz timer
     */
    startTimer() {
        this.timerInterval = setInterval(() => {
            this.timeRemaining--;
            $('#quiz-timer-display').text(this.formatTime(this.timeRemaining));

            // Change timer color when time is running low
            if (this.timeRemaining <= 300) { // 5 minutes
                $('#quiz-timer-display').addClass('time-warning');
            }
            if (this.timeRemaining <= 60) { // 1 minute
                $('#quiz-timer-display').addClass('time-critical');
            }

            // Auto-submit when time is up
            if (this.timeRemaining <= 0) {
                this.stopTimer();
                alert('انتهى الوقت المحدد للكويز. سيتم إرسال إجاباتك تلقائياً.');
                this.submitQuiz(true); // Pass true to indicate timer expiration
            }
        }, 1000);
    }

    /**
     * Stop the quiz timer
     */
    stopTimer() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
            this.timerInterval = null;
        }
    }

    /**
     * Format time in MM:SS format
     */
    formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }

    /**
     * Show loading modal
     */
    showLoadingModal() {
        const loadingHtml = `
            <div id="embedded-quiz-modal" class="quiz-modal-overlay">
                <div class="quiz-modal-container loading">
                    <div class="loading-content">
                        <i class="fas fa-spinner fa-spin fa-3x"></i>
                        <h3>Loading Quiz...</h3>
                    </div>
                </div>
            </div>
        `;

        $('#embedded-quiz-modal').remove();
        $('body').append(loadingHtml);
        $('#embedded-quiz-modal').fadeIn(300);
    }

    /**
     * Show error modal with enhanced UX for different error types
     * @param {string} message - Error message to display
     */
    showErrorModal(message) {
        // Check if this is a maximum attempts error
        const isMaxAttemptsError = message.toLowerCase().includes('maximum attempts') ||
                                   message.toLowerCase().includes('exceeded') ||
                                   message.toLowerCase().includes('استنفاد') ||
                                   message.toLowerCase().includes('المحاولات');

        let modalContent;

        if (isMaxAttemptsError) {
            // Enhanced modal for maximum attempts exceeded
            modalContent = `
                <div class="error-modal-overlay max-attempts-error">
                    <div class="error-modal-content">
                        <div class="error-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h4>Maximum Attempts Exceeded</h4>
                        <p>You have used all available attempts for this quiz.</p>
                        <div class="error-actions">
                            <button class="btn btn-primary" onclick="location.reload()">
                                <i class="fas fa-sync-alt"></i> Refresh Page
                            </button>
                            <button class="btn btn-secondary" onclick="this.closest('.error-modal-overlay').remove()">
                                <i class="fas fa-times"></i> Close
                            </button>
                        </div>
                        <div class="error-help">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                You can review your previous results or move to the next item in the course
                            </small>
                        </div>
                    </div>
                </div>
            `;
        } else {
            // Generic error modal for other errors
            modalContent = `
                <div class="error-modal-overlay">
                    <div class="error-modal-content">
                        <div class="error-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h4>An Error Occurred</h4>
                        <p>${this.escapeHtml(message)}</p>
                        <div class="error-actions">
                            <button class="btn btn-primary" onclick="this.closest('.error-modal-overlay').remove()">
                                <i class="fas fa-times"></i> Close
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Remove any existing error modals
        const existingModals = document.querySelectorAll('.error-modal-overlay');
        existingModals.forEach(modal => modal.remove());

        // Add new modal to body
        document.body.insertAdjacentHTML('beforeend', modalContent);

        // Add click outside to close functionality
        const modal = document.querySelector('.error-modal-overlay');
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });

        // Add escape key to close functionality
        const escapeHandler = (e) => {
            if (e.key === 'Escape') {
                modal.remove();
                document.removeEventListener('keydown', escapeHandler);
            }
        };
        document.addEventListener('keydown', escapeHandler);
    }

    /**
     * Escape HTML to prevent XSS
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize embedded quiz when document is ready
$(document).ready(function() {
    window.embeddedQuiz = new EmbeddedQuiz();

    // Handle quiz start buttons
    $(document).on('click', '.take-embedded-quiz-btn, .start-embedded-quiz', function(e) {
        e.preventDefault();

        const $button = $(this);

        // Check if button is disabled (maximum attempts exceeded)
        if ($button.hasClass('disabled') || $button.prop('disabled')) {
            // Show informative modal instead of trying to start quiz
            window.embeddedQuiz.showErrorModal('تم استنفاد المحاولات المسموحة لهذا الكويز');
            return;
        }

        // Add loading state to button
        const originalText = $button.html();
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> جاري التحميل...');

        const quizId = $button.data('quiz-id');
        const courseSlug = $button.data('course-slug');
        const itemId = $button.data('item-id');

        // Start quiz and handle completion/error
        window.embeddedQuiz.startQuiz(quizId, courseSlug, itemId)
            .finally(() => {
                // Restore button state regardless of success or failure
                $button.prop('disabled', false).html(originalText);
            });
    });
});
