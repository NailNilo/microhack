from django.contrib import admin
from django.urls import path, include

urlpatterns = [
    path('admin/', admin.site.urls),
    path('api/', include('myapp.urls')),
    path('encrypt/', include('myapp.urls')),
    path('', include('myapp.urls')),
]

"""
URL Configuration for MyProject.

This module defines the URL patterns for the MyProject application.
The urlpatterns list contains a set of URL patterns. Each URL pattern
is defined using the path() function, which takes a route string and
a view function as arguments.

The urlpatterns list includes the following patterns:
- 'admin/' route for the Django admin site.
- 'api/' route for the Myapp API.
- 'encrypt/' route for the Myapp encryption functionality.
- Default route for the Myapp application.

For more information on URL patterns, refer to the Django documentation:
https://docs.djangoproject.com/en/3.2/topics/http/urls/
"""
