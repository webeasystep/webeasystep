-- Fix authentication issues
-- 1. Update the email from 'spcialist@gmail.com' to 'specialist@gmail.com'
-- 2. Update the password hash to a working one for '123456'

UPDATE auth_identities 
SET 
    secret = 'specialist@gmail.com',
    secret2 = '$2y$10$YFlUMVlB2IPMZrzXMVcr1O1Ku3oTDY7jeOmQ25R4vLIObKg3pkwFO'
WHERE 
    user_id = 1 
    AND type = 'email_password' 
    AND secret = 'spcialist@gmail.com';

-- Verify the update
SELECT id, user_id, type, secret, secret2 FROM auth_identities WHERE user_id = 1 AND type = 'email_password';