/**
 * Helper function to generate question sets with similar structure
 * @param {string} series - Series identifier (A, B, C, D, E)
 * @param {number} count - Number of questions in the series
 * @param {number} optionCount - Number of options per question
 * @param {number} startIndex - Starting id for questions in this series
 * @returns {Array} - Array of question objects with unique id
 */
function generateQuestionSet(series, count, optionCount, startIndex = 1) {
    const questions = [];

    for (let i = 0; i < count; i++) {
        const questionId = startIndex + i; // ID dimulai dari startIndex dan berurutan

        const question = {
            id: questionId,
            imageUrl: `/images/gambarsoalform/${series}${i + 1}.png`,
            options: [],
        };

        for (let j = 1; j <= optionCount; j++) {
            question.options.push({
                id: j,
                imageUrl: `/images/gambarsoalform/${series}${i + 1}-${j}.png`,
            });
        }

        questions.push(question);
    }

    return questions;
}

export const questionData = [
    ...generateQuestionSet("A", 12, 6, 1), // ID 1 - 12
    ...generateQuestionSet("B", 12, 6, 13), // ID 13 - 24
    ...generateQuestionSet("C", 12, 8, 25), // ID 25 - 36
    ...generateQuestionSet("D", 12, 8, 37), // ID 37 - 48
    ...generateQuestionSet("E", 12, 8, 49), // ID 49 - 60
];
