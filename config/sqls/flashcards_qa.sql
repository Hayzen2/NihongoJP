CREATE TABLE IF NOT EXISTS flashcards_qa (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    flashcard_id INT UNSIGNED NOT NULL,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    FOREIGN KEY (flashcard_id) REFERENCES flashcards(id) ON DELETE CASCADE
)  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO flashcards_qa (flashcard_id, question, answer) VALUES
-- 1 — Japanese Greetings
(1, 'How do you say "hello" in Japanese?', 'こんにちは (Konnichiwa)'),
(1, 'How do you say "good morning"?', 'おはよう (Ohayou)'),
(1, 'How do you say "good evening"?', 'こんばんは (Konbanwa)'),
(1, 'How do you say "good night"?', 'おやすみ (Oyasumi)'),
(1, 'How do you say "nice to meet you"?', 'はじめまして (Hajimemashite)'),


-- 2 — Japanese Numbers
(2, 'What is the Japanese word for "one"?', 'いち (Ichi)'),
(2, 'What is the Japanese word for "two"?', 'に (Ni)'),
(2, 'What is the Japanese word for "three"?', 'さん (San)'),
(2, 'What is the Japanese word for "four"?', 'よん / し (Yon / Shi)'),
(2, 'What is the Japanese word for "five"?', 'ご (Go)'),


-- 3 — Japanese Food Vocabulary
(3, 'What is the Japanese word for "rice"?', 'ごはん (Gohan)'),
(3, 'What is the Japanese word for "fish"?', 'さかな (Sakana)'),
(3, 'What is the Japanese word for "meat"?', 'にく (Niku)'),
(3, 'What is the Japanese word for "vegetables"?', 'やさい (Yasai)'),
(3, 'How do you say "delicious"?', 'おいしい (Oishii)'),


-- 4 — Japanese Travel Phrases
(4, 'How do you say "Where is the station?"', 'えきはどこですか (Eki wa doko desu ka?)'),
(4, 'How do you say "I need help"?', 'たすけてください (Tasukete kudasai)'),
(4, 'How do you say "How much is this?"', 'これはいくらですか (Kore wa ikura desu ka?)'),
(4, 'How do you say "I am lost"?', 'みちにまよいました (Michi ni mayoimashita)'),
(4, 'How do you say "Please take me to this place"?', 'ここへつれていってください (Koko e tsurete itte kudasai)'),


-- 5 — Basic Kanji Set 1
(5, 'What is the meaning of the kanji 日?', 'Sun / day'),
(5, 'What is the meaning of the kanji 月?', 'Moon / month'),
(5, 'What is the meaning of the kanji 山?', 'Mountain'),
(5, 'What is the meaning of the kanji 水?', 'Water'),
(5, 'What is the meaning of the kanji 火?', 'Fire'),


-- 6 — Japanese Colors
(6, 'How do you say "red" in Japanese?', 'あか (Aka)'),
(6, 'How do you say "blue" in Japanese?', 'あお (Ao)'),
(6, 'How do you say "green" in Japanese?', 'みどり (Midori)'),
(6, 'How do you say "yellow" in Japanese?', 'きいろ (Kiiro)'),
(6, 'How do you say "black" in Japanese?', 'くろ (Kuro)'),


-- 7 — Japanese Family Terms
(7, 'How do you say "mother" in Japanese?', 'おかあさん (Okaasan)'),
(7, 'How do you say "father" in Japanese?', 'おとうさん (Otousan)'),
(7, 'How do you say "older sister"?', 'おねえさん (Oneesan)'),
(7, 'How do you say "older brother"?', 'おにいさん (Oniisan)'),
(7, 'How do you say "family"?', 'かぞく (Kazoku)'),


-- 8 — Japanese Verbs Basic
(8, 'What is the Japanese verb for "eat"?', 'たべる (Taberu)'),
(8, 'What is the Japanese verb for "drink"?', 'のむ (Nomu)'),
(8, 'What is the Japanese verb for "go"?', 'いく (Iku)'),
(8, 'What is the Japanese verb for "come"?', 'くる (Kuru)'),
(8, 'What is the Japanese verb for "see/watch"?', 'みる (Miru)'),


-- 9 — Japanese Time & Days
(9, 'How do you say "today" in Japanese?', 'きょう (Kyou)'),
(9, 'How do you say "tomorrow"?', 'あした (Ashita)'),
(9, 'How do you say "yesterday"?', 'きのう (Kinou)'),
(9, 'What is the word for "week"?', 'しゅう (Shuu)'),
(9, 'What is the word for "year"?', 'ねん (Nen)'),


-- 10 — Basic Kanji Set 2
(10, 'What is the meaning of the kanji 人?', 'Person'),
(10, 'What is the meaning of the kanji 木?', 'Tree'),
(10, 'What is the meaning of the kanji 口?', 'Mouth'),
(10, 'What is the meaning of the kanji 手?', 'Hand'),
(10, 'What is the meaning of the kanji 目?', 'Eye'),

-- 11 — Japanese Animals
(11, 'How do you say "dog" in Japanese?', 'いぬ (Inu)'),
(11, 'How do you say "cat"?', 'ねこ (Neko)'),
(11, 'How do you say "bird"?', 'とり (Tori)'),
(11, 'How do you say "horse"?', 'うま (Uma)'),
(11, 'How do you say "fish"?', 'さかな (Sakana)'),


-- 12 — Japanese Weather Words
(12, 'How do you say "rain" in Japanese?', 'あめ (Ame)'),
(12, 'How do you say "snow"?', 'ゆき (Yuki)'),
(12, 'How do you say "wind"?', 'かぜ (Kaze)'),
(12, 'How do you say "cloud"?', 'くも (Kumo)'),
(12, 'How do you say "sunny"?', 'はれ (Hare)'),


-- 13 — Japanese Classroom Terms
(13, 'How do you say "teacher" in Japanese?', 'せんせい (Sensei)'),
(13, 'How do you say "student"?', 'がくせい (Gakusei)'),
(13, 'How do you say "school"?', 'がっこう (Gakkou)'),
(13, 'How do you say "desk"?', 'つくえ (Tsukue)'),
(13, 'How do you say "book"?', 'ほん (Hon)'),


-- 14 — Japanese Emotions
(14, 'How do you say "happy" in Japanese?', 'しあわせ (Shiawase)'),
(14, 'How do you say "sad"?', 'かなしい (Kanashii)'),
(14, 'How do you say "angry"?', 'おこっている (Okotte iru)'),
(14, 'How do you say "scared"?', 'こわい (Kowai)'),
(14, 'How do you say "tired"?', 'つかれた (Tsukareta)'),


-- 15 — Basic Kanji Set 3
(15, 'What is the meaning of the kanji 空?', 'Sky / empty'),
(15, 'What is the meaning of the kanji 金?', 'Gold / money'),
(15, 'What is the meaning of the kanji 石?', 'Stone'),
(15, 'What is the meaning of the kanji 車?', 'Car / vehicle'),
(15, 'What is the meaning of the kanji 川?', 'River'),


-- 16 — Japanese Clothing Vocabulary
(16, 'How do you say "shirt" in Japanese?', 'シャツ (Shatsu)'),
(16, 'How do you say "pants"?', 'ズボン (Zubon)'),
(16, 'How do you say "shoes"?', 'くつ (Kutsu)'),
(16, 'How do you say "hat"?', 'ぼうし (Boushi)'),
(16, 'How do you say "dress"?', 'ドレス (Doresu)'),


-- 17 — Japanese Hobbies
(17, 'How do you say "reading" in Japanese?', 'どくしょ (Dokusho)'),
(17, 'How do you say "sports"?', 'スポーツ (Supōtsu)'),
(17, 'How do you say "music"?', 'おんがく (Ongaku)'),
(17, 'How do you say "traveling"?', 'りょこう (Ryokou)'),
(17, 'How do you say "games"?', 'ゲーム (Gēmu)'),


-- 18 — Japanese Transportation Words
(18, 'How do you say "train" in Japanese?', 'でんしゃ (Densha)'),
(18, 'How do you say "bus"?', 'バス (Basu)'),
(18, 'How do you say "airplane"?', 'ひこうき (Hikouki)'),
(18, 'How do you say "car"?', 'くるま (Kuruma)'),
(18, 'How do you say "bicycle"?', 'じてんしゃ (Jitensha)'),


-- 19 — Japanese Nature Words
(19, 'How do you say "forest" in Japanese?', 'もり (Mori)'),
(19, 'How do you say "river"?', 'かわ (Kawa)'),
(19, 'How do you say "sea"?', 'うみ (Umi)'),
(19, 'How do you say "mountain"?', 'やま (Yama)'),
(19, 'How do you say "flower"?', 'はな (Hana)'),


-- 20 — Basic Kanji Set 4
(20, 'What is the meaning of the kanji 足?', 'Leg / foot'),
(20, 'What is the meaning of the kanji 耳?', 'Ear'),
(20, 'What is the meaning of the kanji 草?', 'Grass'),
(20, 'What is the meaning of the kanji 雨?', 'Rain'),
(20, 'What is the meaning of the kanji 空?', 'Sky');