from django.shortcuts import render
from django.http import JsonResponse
import os
from django.views.decorators.csrf import csrf_exempt
from django.http import JsonResponse
from django.conf import settings


def upload_song(request):
    return JsonResponse({"message": "Upload endpoint is working!"})


@csrf_exempt  # Only use this if you're not passing a CSRF token from frontend
def upload_song(request):
    if request.method == 'POST' and request.FILES.get('file'):
        uploaded_file = request.FILES['file']

        # Define where to save the file
        file_path = os.path.join(settings.MEDIA_ROOT, 'songs', uploaded_file.name)

        # Create the 'songs' folder if it doesn't exist
        os.makedirs(os.path.dirname(file_path), exist_ok=True)

        with open(file_path, 'wb+') as destination:
            for chunk in uploaded_file.chunks():
                destination.write(chunk)

        file_url = f"{settings.MEDIA_URL}songs/{uploaded_file.name}"
        return JsonResponse({'message': 'Upload successful!', 'url': file_url})

    return JsonResponse({'error': 'No file uploaded or wrong method.'}, status=400)


# Create your views here.
