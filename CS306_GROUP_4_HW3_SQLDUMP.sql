-- Create database
CREATE DATABASE IF NOT EXISTS cs306_project;
USE cs306_project;

-- USERS TABLE
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100)
);

INSERT INTO users (name, email) VALUES
('Ahmet Batuhan Baykal', 'ahmet.baykal@example.com'),
('Cağlar Uysal', 'caglar.uysal@example.com'),
('Doğukan Doğan', 'dogukan.dogan@example.com'),
('Mehmet Yılmaz', 'mehmet.yilmaz@example.com'),
('Ayşe Demir', 'ayse.demir@example.com'),
('Fatma Kaya', 'fatma.kaya@example.com'),
('Ali Öztürk', 'ali.ozturk@example.com'),
('Zeynep Şahin', 'zeynep.sahin@example.com'),
('Mustafa Çelik', 'mustafa.celik@example.com'),
('Elif Yıldız', 'elif.yildiz@example.com');

-- ORDERS TABLE
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    amount DECIMAL(10,2),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO orders (user_id, amount) VALUES
(1, 150.50),
(2, 299.99),
(3, 75.25),
(4, 1200.00),
(5, 45.99),
(1, 89.99),
(2, 199.50),
(3, 649.99),
(4, 25.00),
(5, 159.99),
(6, 899.99),
(7, 1500.00),
(8, 79.99),
(9, 299.99),
(10, 449.99);

-- PRODUCTS TABLE
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    price DECIMAL(10,2)
);

INSERT INTO products (name, price) VALUES
('Premium Widget', 49.99),
('Super Gadget', 99.99),
('Ultra Thingamajig', 29.99),
('Smart Device', 199.99),
('Tech Accessory', 39.99),
('Digital Helper', 149.99),
('Modern Tool', 79.99),
('Innovation Plus', 299.99),
('Tech Wonder', 249.99),
('Smart Solution', 179.99),
('Budget Widget', 19.99),
('Basic Gadget', 59.99);

-- ARCHIVED PRODUCTS TABLE
CREATE TABLE archived_products (
    id INT,
    name VARCHAR(100),
    price DECIMAL(10,2),
    deleted_at DATETIME
);

INSERT INTO archived_products (id, name, price, deleted_at) VALUES
(13, 'Old Widget', 15.99, '2024-01-15 10:30:00'),
(14, 'Discontinued Gadget', 89.99, '2024-02-01 14:45:00'),
(15, 'Legacy Tool', 129.99, '2024-02-15 09:15:00');

-- REVIEWS TABLE
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    review_text TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

INSERT INTO reviews (user_id, product_id, review_text) VALUES
(1, 1, 'Great product! Exactly what I needed.'),
(2, 1, 'Good quality and fast delivery.'),
(3, 2, 'Amazing gadget, works perfectly!'),
(4, 2, 'Decent product but a bit pricey.'),
(5, 3, 'SPAM! Buy cheap products at spammy-site.com!'),
(6, 3, 'Very useful tool, highly recommend.'),
(7, 4, 'Not what I expected, but still good.'),
(8, 4, 'Perfect for my needs, will buy again.'),
(9, 5, 'FREE OFFER! Click here: bit.ly/suspicious-link'),
(10, 5, 'Excellent quality and great value.'),
(1, 6, 'Works as advertised, very satisfied.'),
(2, 6, 'Could be better, but does the job.'),
(3, 7, 'Amazing product, exceeded expectations!');

-- SUPPORT_TICKETS TABLE
CREATE TABLE support_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    message TEXT,
    status BOOLEAN,
    created_at DATETIME
);

INSERT INTO support_tickets (username, message, status, created_at) VALUES
('system', 'Order amount warning for user 4: High amount detected - 1200.00', 1, '2024-03-01 10:15:00'),
('system', 'Order amount warning for user 7: High amount detected - 1500.00', 1, '2024-03-02 14:30:00'),
('Ahmet Batuhan Baykal', 'Issue with order #1234', 1, '2024-03-03 09:45:00'),
('Cağlar Uysal', 'Product inquiry', 0, '2024-03-03 11:20:00'),
('Doğukan Doğan', 'Delivery delay', 1, '2024-03-04 13:10:00'),
('Mehmet Yılmaz', 'Payment issue', 1, '2024-03-04 15:45:00'),
('Ayşe Demir', 'Return request', 0, '2024-03-05 10:30:00');

-- USER EMAIL CHANGES TABLE
CREATE TABLE user_email_changes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    old_email VARCHAR(100),
    new_email VARCHAR(100),
    changed_at DATETIME,
    change_type VARCHAR(20)
);

INSERT INTO user_email_changes (user_id, old_email, new_email, changed_at, change_type) VALUES
(1, 'old.ahmet@example.com', 'ahmet.baykal@example.com', '2024-02-01 10:00:00', 'VALID_CHANGE'),
(2, 'caglar.old@example.com', 'caglar.uysal@example.com', '2024-02-05 11:30:00', 'VALID_CHANGE'),
(3, 'dogukan.invalid', 'dogukan.dogan@example.com', '2024-02-10 14:15:00', 'INVALID_FORMAT');

-- SPAM REVIEWS TABLE
CREATE TABLE spam_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    review_id INT,
    detected_at DATETIME
);

INSERT INTO spam_reviews (review_id, detected_at) VALUES
(5, '2024-03-01 15:30:00'),
(9, '2024-03-02 16:45:00');

-- TRIGGER 1: Log warning if order amount > 1000 or < 0
DELIMITER $$
CREATE TRIGGER trg_order_amount_warning
AFTER INSERT ON orders
FOR EACH ROW
BEGIN
    IF NEW.amount > 1000 OR NEW.amount < 0 THEN
        INSERT INTO support_tickets (username, message, status, created_at)
        VALUES (
            'system', 
            CASE 
                WHEN NEW.amount > 1000 THEN CONCAT('Order amount warning for user ', NEW.user_id, ': High amount detected - ', NEW.amount)
                ELSE CONCAT('Order amount warning for user ', NEW.user_id, ': Negative amount detected - ', NEW.amount)
            END,
            1, 
            NOW()
        );
    END IF;
END$$
DELIMITER ;

-- TRIGGER 2: Track email changes
DELIMITER $$
CREATE TRIGGER trg_user_email_update
BEFORE UPDATE ON users
FOR EACH ROW
BEGIN
    DECLARE change_type VARCHAR(20);
    
    -- Determine the type of email change
    IF NEW.email = OLD.email THEN
        SET change_type = 'NO_CHANGE';
    ELSEIF NEW.email NOT LIKE '%@%.%' THEN
        SET change_type = 'INVALID_FORMAT';
    ELSE
        SET change_type = 'VALID_CHANGE';
    END IF;
    
    -- Log the change with additional context
    INSERT INTO user_email_changes (user_id, old_email, new_email, changed_at, change_type)
    VALUES (
        NEW.id, 
        OLD.email, 
        NEW.email, 
        NOW(),
        change_type
    );
END$$
DELIMITER ;

-- TRIGGER 3: Archive deleted products
DELIMITER $$
CREATE TRIGGER trg_product_delete_archive
BEFORE DELETE ON products
FOR EACH ROW
BEGIN
    -- Check if the product has any reviews
    DECLARE review_count INT;
    SELECT COUNT(*) INTO review_count 
    FROM reviews 
    WHERE product_id = OLD.id;
    
    -- Log if product has reviews (they will be automatically deleted due to CASCADE)
    IF review_count > 0 THEN
        INSERT INTO support_tickets (username, message, status, created_at)
        VALUES (
            'system',
            CONCAT('Product ID ', OLD.id, ' deleted with ', review_count, ' associated reviews (auto-deleted)'),
            1,
            NOW()
        );
    END IF;
    
    -- Archive the product
    INSERT INTO archived_products (id, name, price, deleted_at)
    VALUES (
        OLD.id,
        OLD.name,
        OLD.price,
        NOW()
    );
END$$
DELIMITER ;

-- TRIGGER 4: Check for spam in reviews
DELIMITER $$
CREATE TRIGGER trg_review_spam_check
AFTER INSERT ON reviews
FOR EACH ROW
BEGIN
    IF LOWER(NEW.review_text) LIKE '%spam%' THEN
        INSERT INTO spam_reviews (review_id, detected_at)
        VALUES (NEW.id, NOW());
    END IF;
END$$
DELIMITER ;

-- PROCEDURE 1: get_user_orders
DELIMITER $$
CREATE PROCEDURE get_user_orders(IN uid INT)
BEGIN
    SELECT * FROM orders WHERE user_id = uid;
END$$
DELIMITER ;

-- PROCEDURE 2: update_product_price
DELIMITER $$
CREATE PROCEDURE update_product_price(IN pid INT, IN new_price DECIMAL(10,2))
BEGIN
    UPDATE products SET price = new_price WHERE id = pid;
END$$
DELIMITER ;

-- PROCEDURE 3: get_active_tickets
DELIMITER $$
CREATE PROCEDURE get_active_tickets()
BEGIN
    SELECT * FROM support_tickets WHERE status = 1;
END$$
DELIMITER ;

-- PROCEDURE 4: add_review
DELIMITER $$
CREATE PROCEDURE add_review(IN uid INT, IN pid INT, IN rtext TEXT)
BEGIN
    INSERT INTO reviews (user_id, product_id, review_text) VALUES (uid, pid, rtext);
END$$
DELIMITER ;
