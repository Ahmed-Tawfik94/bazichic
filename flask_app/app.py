from flask import Flask
# from flask_sqlalchemy import SQLAlchemy # No longer directly initialized here
from flask_migrate import Migrate
from .config import Config # Changed to relative import
from .extensions import db # Import db from extensions

app = Flask(__name__)
app.config.from_object(Config)

# Initialize extensions
db.init_app(app)
migrate = Migrate(app, db, directory='flask_app/migrations') # Specified migrations directory

# Import models here to ensure they are registered with SQLAlchemy before Alembic/Flask-Migrate commands are run
# Ensure models import db from .extensions as well
from flask_app.models.user import User, user_roles
from flask_app.models.role import Role
from flask_app.models.document import Document
from flask_app.models.plan import Plan
from flask_app.models.subscription import Subscription
from flask_app.models.category import Category
from flask_app.models.document_type import DocumentType
from flask_app.models.faq_category import FaqCategory
from flask_app.models.faq_subcategory import FaqSubCategory
from flask_app.models.faq import Faq
from flask_app.models.payment import Payment
from flask_app.models.notification import Notification
from flask_app.models.document_like import DocumentLike
from flask_app.models.reward_point import RewardPoint
from flask_app.models.referral import Referral
from flask_app.models.redeem_transaction import RedeemTransaction
from flask_app.models.activity import Activity
from flask_app.models.document_review import DocumentReview
from flask_app.models.site_setting import SiteSetting
from flask_app.models.doc_keyword import DocKeyword
from flask_app.models.document_audio import DocumentAudio
from flask_app.models.document_save import DocumentSave
from flask_app.models.document_view import DocumentView
from flask_app.models.email_verification import EmailVerification
from flask_app.models.contact import Contact
from flask_app.models.invoice import Invoice
from flask_app.models.status import Status
from flask_app.models.store_tag import StoreTag
from flask_app.models.free_trial import FreeTrial
from flask_app.models.app_usage import AppUsage
from flask_app.models.testimonial import Testimonial
from flask_app.models.currency import Currency

# Import Blueprints
from flask_app.routes.main_routes import main_bp
from flask_app.routes.auth_routes import auth_bp
from flask_app.routes.admin.admin_main_routes import admin_main_bp
from flask_app.routes.ebook_routes import ebook_bp
from flask_app.routes.subscription_routes import subscription_bp
from flask_app.routes.calendar_routes import calendar_bp
from flask_app.routes.dashboard_routes import dashboard_bp
from flask_app.routes.referral_routes import referral_bp
from flask_app.routes.payment_routes import payment_bp
from flask_app.routes.document_actions_routes import doc_actions_bp
# New Admin Blueprints
from flask_app.routes.admin.admin_document_routes import admin_doc_bp
from flask_app.routes.admin.admin_faq_routes import admin_faq_bp
from flask_app.routes.admin.admin_category_routes import admin_cat_bp
from flask_app.routes.admin.admin_plan_routes import admin_plan_bp
from flask_app.routes.admin.admin_subscription_routes import admin_subscription_bp
from flask_app.routes.admin.admin_reward_routes import admin_reward_bp
from flask_app.routes.admin.admin_user_routes import admin_user_bp
from flask_app.routes.admin.admin_system_routes import admin_system_bp # Import admin_system_bp
from flask_app.routes.admin.admin_notification_routes import admin_notification_bp # Import admin_notification_bp


# Register Blueprints
app.register_blueprint(main_bp)
app.register_blueprint(auth_bp)
app.register_blueprint(admin_main_bp) # Handles /admin/ (dashboard)
app.register_blueprint(ebook_bp)
app.register_blueprint(subscription_bp)
app.register_blueprint(calendar_bp)
app.register_blueprint(dashboard_bp)
app.register_blueprint(referral_bp)
app.register_blueprint(payment_bp)
app.register_blueprint(doc_actions_bp)
# Register new Admin Blueprints
app.register_blueprint(admin_doc_bp) # Handles /admin/documents/*
app.register_blueprint(admin_faq_bp) # Handles /admin/faqs/*
app.register_blueprint(admin_cat_bp) # Handles /admin/categories/*
app.register_blueprint(admin_plan_bp)
app.register_blueprint(admin_subscription_bp)
app.register_blueprint(admin_reward_bp)
app.register_blueprint(admin_user_bp)
app.register_blueprint(admin_system_bp) # Register admin_system_bp
app.register_blueprint(admin_notification_bp) # Register admin_notification_bp

# main driver function
if __name__ == '__main__':
    # app.run(debug=True) # Typically not run this way when using Flask CLI / migrations
    # The Flask CLI (`flask run`) is preferred for development.
    pass
