from django.db import models
from django.contrib.auth.models import User

class Document(models.Model):
    """
    Represents a document uploaded by a user.

    Attributes:
        file (FileField): The uploaded file.
        user (ForeignKey): The user who uploaded the document.
        uploaded_at (DateTimeField): The date and time when the document was uploaded.
    """

    file = models.FileField(upload_to='documents/')
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    uploaded_at = models.DateTimeField(auto_now_add=True)

    def __str__(self):
        return self.file.name
