# 🇯🇵 NihongoJP – Japanese Learning Web App

An interactive web platform to help learners master **Japanese vocabulary, grammar, and kanji**.  
Structured lessons, quizzes, flashcards, and an AI chatbot make learning engaging and effective.

---

## 🚀 Features

### 📖 Vocabulary Practice
- Study words categorized by **JLPT levels (N5 → N1)**.
- Track learned vocabulary and monitor progress over time.

### ✍️ Grammar Lessons
- Clear, concise explanations.
- Real-world usage examples for easy comprehension.

### 🈷️ Kanji Learning
- Learn **kanji by JLPT level** with readings, meanings, and stroke order animations.
- Practice recognition and writing interactively.

### 🎴 Flashcards & Quizzes
- Interactive flashcards for words, grammar, and kanji.
- Chapter-based quizzes to test your knowledge and reinforce learning.

### 📚 Learning Materials
- Access curated **Japanese learning books** and resources directly from the platform.

### 📊 Progress Tracking
- Monitor quiz results and achievements.
- Visualize learning progress to stay motivated.

### 🤖 AI Chatbot
- Integrated AI assistant (Gemini-powered) to answer questions about lessons, examples, and vocabulary.

### 🖌️ Handwriting Recognition
- Draw kanji, hiragana, or katakana on a canvas.
- Real-time stroke validation with search functionality for words and kanji.

---

## 🔮 Future Features
- Subscription-based premium content.
- More interactive exercises and gamification elements.

---

## 💻 Tech Stack

### Frontend
- **HTML5, CSS3, JavaScript** – Core web technologies for structure, styling, and interactivity.
- **Bootstrap 5** – Responsive layout and pre-built UI components.
- **jQuery & jQuery UI** – DOM manipulation, animations, and UI widgets.
- **Interact.js** – Drag-and-drop and interactive UI elements.

### Backend
- **PHP 8+** – Server-side logic and templating.
- **Composer** – Dependency management.
- **PHP Libraries**:
  - `vlucas/phpdotenv` – Environment variable management
  - `google/apiclient` – Google API integrations (e.g., Google Sign-In)
  - `phpmailer/phpmailer` – Sending emails
  - `erusev/parsedown` – Markdown parsing

### Database
- **MySQL** – Relational database for user data, lessons, quizzes, and progress tracking

### AI & Chatbot
- **Gemini API** – AI assistant for grammar, vocabulary, and kanji support

### Tools & Hosting
- **XAMPP** – Local development (Apache + MySQL)
- **CDNs** – For Bootstrap, icons, and jQuery

---

## 📂 Getting Started

### Prerequisites
Before running the project, make sure you have the following installed:

- **XAMPP** (includes Apache and MySQL)
- **Composer** (for managing PHP dependencies) [Download here](https://getcomposer.org/download/)

### Installation Steps
1. Clone the repository:
  ```bash
  git clone https://github.com/Hayzen2/NihongoJP.git
  cd NihongoJP
2. Install PHP dependencies using Composer:
  ```composer install
3. Create a `.env` file in the project folder.
4. Fill in the necessary fields in `.env` (see `example-env.txt` for reference).
5. Start Apache and MySQL using XAMPP (The database will be automatically created and imported)
6. Open your browser and navigate to: http://localhost/NihongoJP

## 🎯 Goal
Make **Japanese learning fun, interactive, and effective** for learners at all levels.