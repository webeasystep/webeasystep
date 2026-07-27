# Quiz Import JSON Format Guide

## Overview
This guide provides a complete reference for importing quizzes using JSON format in the MSARLink platform. The system supports 5 different question types with various configuration options.

## JSON Structure

### Root Level Properties
```json
{
  "quiz_title": "string (required)",
  "quiz_description": "string (optional, alias: quiz_desc)", 
  "course_id": "integer (required - will be overridden by UI selection)",
  "time_limit": "integer (minutes, optional, alias: time_limit_minutes)",
  "time_limit_minutes": "integer (alias for time_limit)",
  "passing_score": "float (0-100, default: 70.00)",
  "max_attempts": "integer (default: 3)",
  "shuffle_questions": "boolean (default: false)",
  "shuffle_answers": "boolean (default: false)", 
  "show_results": "boolean (default: true)",
  "show_results_immediately": "boolean (default: false)",
  "questions": "array (required, alias: quiz_questions)"
}
```

> **ملاحظة:** يقبل النظام أسماء مفاتيح بديلة لتوافق أكبر مع ملفات JSON المختلفة:
> - `questions` **أو** `quiz_questions` → مصفوفة الأسئلة
> - `quiz_description` **أو** `quiz_desc` → وصف الاختبار
> - `time_limit` **أو** `time_limit_minutes` → مدة الاختبار بالدقائق

## Question Types

### 1. Single Choice Questions
**Type:** `single_choice`
**Description:** Multiple choice with one correct answer

```json
{
  "question_text": "What is the correct syntax for declaring a variable in JavaScript?",
  "question_type": "single_choice",
  "points": 2,
  "options": [
    "var myVariable;",
    "variable myVariable;", 
    "declare myVariable;",
    "create myVariable;"
  ],
  "correct_answer": "var myVariable;",
  "explanation": "Optional explanation text"
}
```

**Required Fields:**
- `question_text`: The question content
- `question_type`: Must be "single_choice"
- `points`: Point value for the question
- `options`: Array of answer choices
- `correct_answer`: The exact text of the correct option

**Optional Fields:**
- `explanation`: Explanation shown after answering

### 2. Multiple Choice Questions  
**Type:** `multiple_choice`
**Description:** Multiple choice with multiple correct answers

```json
{
  "question_text": "Which are valid JavaScript data types?",
  "question_type": "multiple_choice",
  "points": 3,
  "options": [
    "String",
    "Number", 
    "Boolean",
    "Integer",
    "Object"
  ],
  "correct_answer": ["String", "Number", "Boolean", "Object"],
  "explanation": "Integer is not a separate type in JavaScript"
}
```

**Required Fields:**
- `question_text`: The question content
- `question_type`: Must be "multiple_choice"
- `points`: Point value for the question
- `options`: Array of answer choices
- `correct_answer`: Array of correct option texts

**Optional Fields:**
- `explanation`: Explanation shown after answering

### 3. True/False Questions
**Type:** `true_false`
**Description:** Binary true or false questions

```json
{
  "question_text": "JavaScript is a compiled programming language.",
  "question_type": "true_false", 
  "points": 1,
  "correct_answer": "false",
  "explanation": "JavaScript is interpreted, not compiled"
}
```

**Required Fields:**
- `question_text`: The question content
- `question_type`: Must be "true_false"
- `points`: Point value for the question
- `correct_answer`: Either "true" or "false"

**Optional Fields:**
- `explanation`: Explanation shown after answering

### 4. Fill in the Blank Questions
**Type:** `fill_in_blank`
**Description:** Questions requiring text input to complete

```json
{
  "question_text": "Complete the code: function add(a,b) { return ______; }",
  "question_type": "fill_in_blank",
  "points": 2,
  "correct_answer": "a + b",
  "alternative_answers": ["a+b", "(a + b)", "(a+b)"],
  "case_sensitive": false,
  "explanation": "The function should return the sum of a and b"
}
```

**Required Fields:**
- `question_text`: The question content (use ______ for blank)
- `question_type`: Must be "fill_in_blank"
- `points`: Point value for the question
- `correct_answer`: The primary correct answer

**Optional Fields:**
- `alternative_answers`: Array of alternative correct answers
- `case_sensitive`: Boolean, default false
- `explanation`: Explanation shown after answering

### 5. Essay Questions
**Type:** `essay`
**Description:** Long-form text responses requiring manual grading

```json
{
  "question_text": "Explain the difference between '==' and '===' in JavaScript.",
  "question_type": "essay",
  "points": 5,
  "max_words": 200,
  "min_words": 50,
  "grading_criteria": [
    "Explains type coercion in == operator",
    "Explains strict equality in === operator",
    "Provides correct examples"
  ],
  "sample_answer": "The '==' operator performs type coercion..."
}
```

**Required Fields:**
- `question_text`: The question content
- `question_type`: Must be "essay"
- `points`: Point value for the question

**Optional Fields:**
- `max_words`: Maximum word limit
- `min_words`: Minimum word requirement
- `grading_criteria`: Array of grading criteria for instructors
- `sample_answer`: Sample answer for reference
- `explanation`: Additional context or instructions

## Import Process

### Step 1: Prepare JSON File
1. Create a JSON file following the structure above
2. Validate JSON syntax using a JSON validator
3. Ensure all required fields are present
4. Test with a small quiz first

### Step 2: Access Import Interface
1. Navigate to Admin → Quizzes → Import
2. Select the target course from dropdown
3. Choose your JSON file
4. Click "Import Quiz"

### Step 3: Verify Import
1. Check the import results page for any errors
2. Review the imported quiz in the quiz list
3. Test the quiz functionality
4. Check debug logs if issues occur

## Best Practices

### Question Design
- Keep question text clear and concise
- Provide meaningful explanations
- Use appropriate point values (1-5 typically)
- Test questions before importing large batches

### Answer Options
- Make distractors plausible but clearly wrong
- Avoid "all of the above" or "none of the above" 
- Keep option length similar
- Randomize option order when possible

### Technical Considerations
- Escape special characters in JSON strings
- Use UTF-8 encoding for international characters
- Keep file size reasonable (< 1MB recommended)
- Backup existing quizzes before importing

### Error Handling
- Always check debug logs after import
- Validate JSON structure before upload
- Test with small samples first
- Keep original files as backup

## Common Issues

### Import Failures
- **Invalid JSON syntax**: Use JSON validator
- **Missing required fields**: Check all required properties
- **Invalid question types**: Use only supported types
- **Course not found**: Verify course_id exists
- **"بنية JSON للاختبار غير صالحة"**: تحقق من الأمور التالية:
  - يجب أن يحتوي الملف على مفتاح `questions` أو `quiz_questions`
  - يجب أن يكون `question_text` موجوداً في كل سؤال
  - يجب أن يكون `question_type` موجوداً في كل سؤال
  - أسئلة `single_choice` و `multiple_choice` تحتاج إلى مصفوفة `options`
  - يجب وجود `correct_answer` في كل سؤال

### Question Display Issues
- **Options not showing**: Check options array format
- **Correct answers not working**: Verify exact text match
- **Encoding problems**: Use UTF-8 encoding

### Performance Issues
- **Large files**: Split into smaller batches
- **Many questions**: Consider pagination
- **Complex questions**: Simplify where possible

## Example Files
- `quiz_import_example.json`: Complete example with all question types
- Check `/modules/Quizzes/Views/Admin/import.php` for UI examples
- Review existing quizzes for format reference

## Support
- Check debug logs in `writable/logs/`
- Review import errors in admin interface
- Test individual question types separately
- Contact system administrator for database issues