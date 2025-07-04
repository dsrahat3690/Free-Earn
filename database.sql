
-- ইউজারদের তথ্য সংরক্ষণের টেবিল
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  mobile VARCHAR(20),
  password VARCHAR(255) NOT NULL,
  referral_code VARCHAR(20) UNIQUE,
  referred_by VARCHAR(20),
  balance DOUBLE DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- উত্তোলন অনুরোধ সংরক্ষণের টেবিল
CREATE TABLE withdrawals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  amount DOUBLE NOT NULL,
  method VARCHAR(50) NOT NULL,
  account VARCHAR(100) NOT NULL,
  status VARCHAR(20) DEFAULT 'pending',
  requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
