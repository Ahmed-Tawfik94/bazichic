from flask import Blueprint, render_template

main_bp = Blueprint('main', __name__, template_folder='../../templates/main') # Assuming templates will be organized

@main_bp.route('/')
def home():
    return "Hello from Main Blueprint - Home!"

@main_bp.route('/about')
def about():
    return "Hello from Main Blueprint - About!"

@main_bp.route('/contact', methods=['GET'])
def contact_get():
    return "Hello from Main Blueprint - Contact Form (GET)!"

@main_bp.route('/contact', methods=['POST'])
def contact_post():
    return "Hello from Main Blueprint - Contact Form (POST)!"

@main_bp.route('/testimonials')
def testimonials():
    return "Hello from Main Blueprint - Testimonials!"

@main_bp.route('/coming-soon')
def coming_soon():
    return "Hello from Main Blueprint - Coming Soon!"

@main_bp.route('/check_subscription_status') # Placeholder for original /check_sub
def check_subscription_status_get():
    return "Hello from Main Blueprint - Check Subscription Status (GET)!"
