CREATE TABLE IF NOT EXISTS inquiries (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  public_id CHAR(32) NOT NULL UNIQUE,
  company VARCHAR(255) NOT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  company_type VARCHAR(100) DEFAULT NULL,
  inquiry_type VARCHAR(100) NOT NULL,
  response_style VARCHAR(100) DEFAULT NULL,
  desired_timing VARCHAR(100) DEFAULT NULL,
  budget_range VARCHAR(100) DEFAULT NULL,
  nda VARCHAR(50) DEFAULT NULL,
  contact_method VARCHAR(100) DEFAULT NULL,
  message TEXT NOT NULL,
  privacy_agreed INTEGER NOT NULL DEFAULT 1,
  ip_address VARCHAR(45) DEFAULT NULL,
  user_agent VARCHAR(512) DEFAULT NULL,
  referrer VARCHAR(1024) DEFAULT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'new',
  admin_mail_status VARCHAR(20) NOT NULL DEFAULT 'pending',
  auto_reply_status VARCHAR(20) NOT NULL DEFAULT 'pending',
  admin_mail_sent_at DATETIME DEFAULT NULL,
  auto_reply_sent_at DATETIME DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_inquiries_email_created_at ON inquiries (email, created_at);
CREATE INDEX IF NOT EXISTS idx_inquiries_ip_created_at ON inquiries (ip_address, created_at);
CREATE INDEX IF NOT EXISTS idx_inquiries_status_created_at ON inquiries (status, created_at);

CREATE TRIGGER IF NOT EXISTS trg_inquiries_updated_at
AFTER UPDATE ON inquiries
FOR EACH ROW
BEGIN
  UPDATE inquiries SET updated_at = CURRENT_TIMESTAMP WHERE id = OLD.id;
END;

CREATE TABLE IF NOT EXISTS inquiry_attempts (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  ip_address VARCHAR(45) DEFAULT NULL,
  email VARCHAR(255) DEFAULT NULL,
  result VARCHAR(30) NOT NULL,
  reason VARCHAR(100) DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_attempts_ip_created_at ON inquiry_attempts (ip_address, created_at);
CREATE INDEX IF NOT EXISTS idx_attempts_email_created_at ON inquiry_attempts (email, created_at);
CREATE INDEX IF NOT EXISTS idx_attempts_result_created_at ON inquiry_attempts (result, created_at);
