--SQL DB creation file for database: quotesdb
CREATE DATABASE quotesdb;

-------CREATION-------
--Create my tables
CREATE TABLE authors (
    id SERIAL PRIMARY KEY,
    author VARCHAR(50) NOT NULL
);

CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    category VARCHAR(20) NOT NULL
);

CREATE TABLE quotes (
    id SERIAL PRIMARY KEY,
    quote TEXT NOT NULL,
    author_id INTEGER NOT NULL,
    category_id INTEGER NOT NULL,
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE RESTRICT,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

--Create indexes which help for optimization
CREATE INDEX idx_quotes_author ON quotes (author_id);
CREATE INDEX idx_quotes_category ON quotes (category_id);


-------INSERTS-------
--Fill the authors table
INSERT INTO authors (author) VALUES 
('Albert Einstein'),
('Mark Twain'),
('Maya Angelou'),
('Winston Churchill'),
('Oscar Wilde'),
('Devon Letendre');

--Fill the categories table
INSERT INTO categories (category) VALUES 
('Life'),
('Success'),
('Wisdom'),
('Love'),
('Happiness'),
('Devonisms');

--Fill the quotes table
INSERT INTO quotes (quote, author_id, category_id) VALUES
-- Life Quotes
('Life is like riding a bicycle. To keep your balance, you must keep moving.', 1, 1),
('The two most important days in your life are the day you are born and the day you find out why.', 2, 1),
('You will face many defeats in life, but never let yourself be defeated.', 3, 1),
('Success is not final, failure is not fatal: it is the courage to continue that counts.', 4, 1),
('To live is the rarest thing in the world. Most people exist, that is all.', 5, 1),

-- Success Quotes
('Try not to become a person of success, but rather try to become a person of value.', 1, 2),
('The secret of getting ahead is getting started.', 2, 2),
('We may encounter many defeats but we must not be defeated.', 3, 2),
('If you are going through hell, keep going.', 4, 2),
('Be yourself; everyone else is already taken.', 5, 2),

-- Wisdom Quotes
('A person who never made a mistake never tried anything new.', 1, 3),
('Whenever you find yourself on the side of the majority, it is time to pause and reflect.', 2, 3),
('If you don’t like something, change it. If you can’t change it, change your attitude.', 3, 3),
('The pessimist sees difficulty in every opportunity. The optimist sees opportunity in every difficulty.', 4, 3),
('Always forgive your enemies; nothing annoys them so much.', 5, 3),

-- Love Quotes
('Gravitation is not responsible for people falling in love.', 1, 4),
('The best way to cheer yourself up is to try to cheer somebody else up.', 2, 4),
('If you find it in your heart to care for somebody else, you will have succeeded.', 3, 4),
('My most brilliant achievement was my ability to be able to persuade my wife to marry me.', 4, 4),
('Women are meant to be loved, not to be understood.', 5, 4),

-- Happiness Quotes
('A calm and modest life brings more happiness than the pursuit of success combined with constant restlessness.', 1, 5),
('The human race has one really effective weapon, and that is laughter.', 2, 5),
('Nothing can dim the light which shines from within.', 3, 5),
('Attitude is a little thing that makes a big difference.', 4, 5),
('Some cause happiness wherever they go; others whenever they go.', 5, 5),

--Devonism Quotes
('I am richer than Bill Gates.', 6, 6);



