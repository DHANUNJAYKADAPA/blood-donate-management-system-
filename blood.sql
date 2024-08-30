 Create tables
CREATE TABLE donors (
  id INT PRIMARY KEY,
  name VARCHAR(50),
  blood_type VARCHAR(10),
  email VARCHAR(20),
  contactnumber INT(10), 
  availability VARCHAR(10) DEFAULT 'yes',
  last_donation_date DATE
);



-- Create triggers
CREATE TRIGGER update_donors_trigger
AFTER INSERT ON donations
FOR EACH ROW
BEGIN
    UPDATE donors
    SET availability = 'no', last_donation_date = NEW.donation_date
    WHERE id = NEW.donor_id;
END;

CREATE TRIGGER update_acceptors_trigger
AFTER INSERT ON donations
FOR EACH ROW
BEGIN
    UPDATE acceptors
    SET blood_group_needed = NULL
    WHERE id = NEW.acceptor_id;
END;

CREATE TRIGGER update_donors_trigger_on_delete
AFTER DELETE ON donations
FOR EACH ROW
BEGIN
    UPDATE donors
    SET availability = 'yes'
    WHERE id = OLD.donor_id;
END;

CREATE TRIGGER update_acceptors_trigger_on_delete
AFTER DELETE ON donations
FOR EACH ROW
BEGIN
    UPDATE acceptors
    SET blood_group_needed = OLD.blood_type
    WHERE id = OLD.acceptor_id;
END;

-- Create event to update donated record after 6 months
CREATE EVENT update_donated_record
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
ENDS '2030-12-31 23:59:59'
DO
BEGIN
    UPDATE donors
    SET availability = 'yes'
    WHERE availability = 'no' AND DATEDIFF(CURRENT_DATE, last_donation_date) >= 180;
END;

-- Create trigger to update availability and is_active columns
CREATE TRIGGER update_availability_trigger
AFTER UPDATE OF availability ON donors
FOR EACH ROW
BEGIN
    IF NEW.availability = 'yes' THEN
        UPDATE donations
        SET is_active = 0
        WHERE donor_id = NEW.id;
    END IF;
END;