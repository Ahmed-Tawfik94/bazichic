from flask import Blueprint, render_template

calendar_bp = Blueprint('calendar', __name__, url_prefix='/etongshu', template_folder='../../templates/calendar')

@calendar_bp.route('/')
def show_calendar():
    return "Hello from Calendar Blueprint - Show Calendar (etongshu)!"
