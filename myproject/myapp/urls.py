from django.urls import path
from . import views
from django.conf.urls.static import static
from django.conf import settings

urlpatterns = [
    path('', views.index_view, name='index'),
    path('encrypt/', views.encrypt, name='encrypt'),
    path('decrypt/', views.decrypt, name='decrypt'),
    path('api/upload/', views.upload_document, name='upload_document'),
    path('api/download/<int:document_id>/', views.download_document, name='download_document'),
] + static(settings.STATIC_URL, document_root=settings.STATIC_ROOT)

"""
URL Configuration for the myapp Django application.

This module defines the URL patterns for the views in the myapp application.
The urlpatterns list routes URLs to the corresponding view functions.

URL Patterns:
- '' : Maps to the index_view function, which renders the index page.
- 'encrypt/' : Maps to the encrypt function, which handles encryption requests.
- 'decrypt/' : Maps to the decrypt function, which handles decryption requests.
- 'api/upload/' : Maps to the upload_document function, which handles document upload requests.
- 'api/download/<int:document_id>/' : Maps to the download_document function, which handles document download requests.

Static Files:
The static() function is used to serve static files in development mode.

"""
