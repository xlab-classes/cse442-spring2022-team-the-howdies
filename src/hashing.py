import bcrypt

def password_hash(password):
    # Hash a password for the first time, with a randomly-generated salt
    password = password.encode()
    hashed = bcrypt.hashpw(password, bcrypt.gensalt())
    # Check that an unhashed password matches one that has previously been hashed
    if bcrypt.checkpw(password, hashed):
        print("It matches!")
        return hashed
    else:
        print("It does not match :(")