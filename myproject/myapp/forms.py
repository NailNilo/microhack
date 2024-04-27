from django import forms

class UploadForm(forms.Form):
    """
    A form for uploading files.

    Attributes:
        file (FileField): The file field for uploading files.
    """
    file = forms.FileField()
