from Crypto.Cipher import AES
from Crypto.Random import get_random_bytes
import json

def encrypt_data(data, key):
    """
    Encrypts the given data using AES encryption algorithm.

    Args:
        data (str): The data to be encrypted.
        key (bytes): The encryption key.

    Returns:
        tuple: A tuple containing the encrypted ciphertext, tag, and nonce in hexadecimal format.
    """
    cipher = AES.new(key, AES.MODE_GCM)
    ciphertext, tag = cipher.encrypt_and_digest(data.encode('utf-8'))
    return ciphertext.hex(), tag.hex(), cipher.nonce.hex()
