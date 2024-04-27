from django.http import HttpResponseRedirect, JsonResponse
from django.views.decorators.csrf import csrf_exempt
from django.core.signing import Signer
from Crypto.Cipher import AES
import base64
from django.http import HttpResponse
from django.shortcuts import get_object_or_404, render

from myapp.forms import UploadForm
from .models import Document
import json

def generate_key():
    """
    Generate a random key for encryption.

    Returns:
        str: The generated encryption key.
    """
    signer = Signer()
    return signer.sign('encryption_key')

def index_view(request):
    """
    Render the index.html template.

    Args:
        request (HttpRequest): The HTTP request object.

    Returns:
        HttpResponse: The HTTP response object.
    """
    return render(request, 'index.html')

def encrypt_data(data, key):
    """
    Encrypt the given data using AES encryption.

    Args:
        data (str): The data to be encrypted.
        key (str): The encryption key.

    Returns:
        dict: A dictionary containing the encrypted data, tag, and nonce.
    """
    cipher = AES.new(key.encode(), AES.MODE_GCM)
    ciphertext, tag = cipher.encrypt_and_digest(data.encode())
    return {
        'ciphertext': base64.b64encode(ciphertext).decode(),
        'tag': base64.b64encode(tag).decode(),
        'nonce': base64.b64encode(cipher.nonce).decode()
    }

def decrypt_data(data, key):
    """
    Decrypt the given data using AES decryption.

    Args:
        data (dict): A dictionary containing the encrypted data, tag, and nonce.
        key (str): The encryption key.

    Returns:
        str: The decrypted data.
    """
    try:
        cipher = AES.new(key.encode(), AES.MODE_GCM, nonce=base64.b64decode(data['nonce']))
        decrypted_data = cipher.decrypt_and_verify(base64.b64decode(data['ciphertext']), base64.b64decode(data['tag']))
        return decrypted_data.decode()
    except (ValueError, KeyError):
        return 'Decryption failed'

@csrf_exempt
def encrypt(request):
    """
    Encrypt the data sent in the request.

    Supported content types:
    - application/json
    - multipart/form-data

    Args:
        request (HttpRequest): The HTTP request object.

    Returns:
        JsonResponse: The JSON response containing the encrypted data.
    """
    if request.method == 'POST':
        content_type = request.headers.get('Content-Type', '')
        if 'application/json' in content_type:
            try:
                data = json.loads(request.body)
                plaintext = data.get('plaintext', '')
                encrypted_data = encrypt_data(plaintext)
                return JsonResponse({'encrypted_data': encrypted_data})
            except json.JSONDecodeError:
                return JsonResponse({'error': 'Invalid JSON data'}, status=400)
        elif 'multipart/form-data' in content_type:
            plaintext = request.POST.get('plaintext', '')
            encrypted_data = encrypt_data(plaintext)
            return JsonResponse({'encrypted_data': encrypted_data})
        else:
            return JsonResponse({'error': 'Unsupported content type'}, status=415)
    else:
        return JsonResponse({'error': 'Method not allowed'}, status=405)

@csrf_exempt
def decrypt(request):
    """
    Decrypt the data sent in the request.

    Supported content types:
    - application/json
    - multipart/form-data

    Args:
        request (HttpRequest): The HTTP request object.

    Returns:
        JsonResponse: The JSON response containing the decrypted data.
    """
    if request.method == 'POST':
        content_type = request.headers.get('Content-Type', '')
        if 'application/json' in content_type:
            try:
                data = json.loads(request.body)
                ciphertext = data.get('ciphertext', '')
                decrypted_data = decrypt_data(ciphertext)
                return JsonResponse({'decrypted_data': decrypted_data})
            except json.JSONDecodeError:
                return JsonResponse({'error': 'Invalid JSON data'}, status=400)
        elif 'multipart/form-data' in content_type:
            ciphertext = request.POST.get('ciphertext', '')
            decrypted_data = decrypt_data(ciphertext)
            return JsonResponse({'decrypted_data': decrypted_data})
        else:
            return JsonResponse({'error': 'Unsupported content type'}, status=415)
    else:
        return JsonResponse({'error': 'Method not allowed'}, status=405)

def upload_document(request):
    """
    Handle the upload of a document.

    Args:
        request (HttpRequest): The HTTP request object.

    Returns:
        HttpResponseRedirect: The HTTP redirect response object.
    """
    if request.method == 'POST':
        form = UploadForm(request.POST, request.FILES)
        if form.is_valid():
            uploaded_file = form.cleaned_data['file']
            return HttpResponseRedirect('/success/')
    else:
        form = UploadForm()
    return render(request, 'upload.html', {'form': form})

def download_document(request, document_id):
    """
    Download a document with the given ID.

    Args:
        request (HttpRequest): The HTTP request object.
        document_id (int): The ID of the document to download.

    Returns:
        JsonResponse: The JSON response containing the document ID and file URL.
    """
    document = get_object_or_404(Document, pk=document_id)
    return JsonResponse({'document_id': document.id, 'file_url': document.file.url})