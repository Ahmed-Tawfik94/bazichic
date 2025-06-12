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

@app.route('/')
def hello_world():
    return 'Hello, World!'

# main driver function
if __name__ == '__main__':
    # app.run(debug=True) # Typically not run this way when using Flask CLI / migrations
    pass
