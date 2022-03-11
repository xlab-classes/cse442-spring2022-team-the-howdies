import bcrypt

def password_hash(password):
    # Hash a password for the first time, with a randomly-generated salt
    password = password.encode()
    hashed = bcrypt.hashpw(password, bcrypt.gensalt())
    return hashed
    # Check that an unhashed password matches one that has previously been hashed

def check_password(password, hashed):
    if bcrypt.checkpw(password, hashed):
        print("It matches!")
    else:
        print("It does not match :(")