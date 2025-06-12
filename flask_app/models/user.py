from flask_app.extensions import db  # Import db from extensions
from werkzeug.security import generate_password_hash, check_password_hash
# from flask_login import UserMixin # We'll add this if Flask-Login is fully set up

# Association table for user_roles if using many-to-many
user_roles = db.Table('user_roles',
    db.Column('user_id', db.Integer, db.ForeignKey('users.id'), primary_key=True),
    db.Column('role_id', db.Integer, db.ForeignKey('roles.id'), primary_key=True)
)

class User(db.Model): # Add UserMixin later if needed
    __tablename__ = 'users'

    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(80), unique=True, nullable=False, index=True)
    email = db.Column(db.String(120), unique=True, nullable=False, index=True)
    password_hash = db.Column(db.String(256), nullable=True) # Made nullable
    first_name = db.Column(db.String(50), nullable=True) # Assuming nullable if not always provided
    last_name = db.Column(db.String(50), nullable=True)  # Assuming nullable
    is_active = db.Column(db.Boolean, default=True)
    is_admin = db.Column(db.Boolean, default=False) # Simple admin flag

    stripe_customer_id = db.Column(db.String(120), nullable=True, unique=True, index=True)
    phone = db.Column(db.String(50), nullable=True)
    country = db.Column(db.String(100), nullable=True)
    description = db.Column(db.Text, nullable=True)
    user_image = db.Column(db.String(255), nullable=True) # Path or URL to image
    referral_code = db.Column(db.String(50), unique=True, nullable=True, index=True)
    api_key = db.Column(db.String(120), unique=True, nullable=True, index=True)
    status_id = db.Column(db.Integer, nullable=True) # Could be FK to a UserStatus model later

    ref_user_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_user_ref_user_id', use_alter=True), nullable=True)
    referrer = db.relationship('User', remote_side=[id], backref='referrals', lazy='select') # Self-referential for referrals

    role_id = db.Column(db.Integer, db.ForeignKey('roles.id', name='fk_user_role_id', use_alter=True), nullable=True) # Single role via FK
    # 'role' relationship will be defined here if using this FK, replacing many-to-many 'roles'
    # For now, keeping the many-to-many 'roles' relationship as it's already defined with user_roles table.
    # If a user has only ONE role, the 'roles' many-to-many should be removed and this FK used.
    # The subtask asks for role_id FK, but doesn't explicitly say to remove the many-to-many.
    # I will add the FK, but the 'roles' m2m rel will still exist. This might need clarification in a real scenario.
    # For now, one user can have a primary role_id AND be part of other roles via user_roles.

    # For multiple roles per user (many-to-many) - this was existing
    roles = db.relationship('Role', secondary=user_roles, lazy='subquery',
                            backref=db.backref('users_in_role', lazy=True)) # Changed backref name to avoid conflict if a direct 'users' backref is added from Role

    last_active = db.Column(db.DateTime, nullable=True)
    dob = db.Column(db.Date, nullable=True) # Date of Birth
    reg_source = db.Column(db.String(100), nullable=True) # Registration source, e.g., 'website', 'api', 'admin'

    # Placeholder for subscriptions relationship (existing)
    # subscriptions = db.relationship('Subscription', backref='user', lazy='dynamic')

    # New relationships for this subtask
    notifications_sent = db.relationship('Notification', foreign_keys='Notification.sender_id', back_populates='sender', lazy='dynamic', cascade="all, delete-orphan")
    document_likes = db.relationship('DocumentLike', back_populates='user', lazy='dynamic', cascade="all, delete-orphan")
    reward_points_earned = db.relationship('RewardPoint', back_populates='user', lazy='dynamic', cascade="all, delete-orphan")

    referrals_made = db.relationship('Referral', foreign_keys='Referral.referrer_id', back_populates='referrer', lazy='dynamic', cascade="all, delete-orphan")
    # 'referred_by_relations' is the back_populates name used in Referral.referred_user
    referred_by_relations = db.relationship('Referral', foreign_keys='Referral.referred_id', back_populates='referred_user', lazy='dynamic', cascade="all, delete-orphan")

    redeem_transactions_made = db.relationship('RedeemTransaction', back_populates='user', lazy='dynamic', cascade="all, delete-orphan")

    # New relationships for this subtask (Activity, DocumentReview)
    activities_performed = db.relationship('Activity', foreign_keys='Activity.who_id', back_populates='user', lazy='dynamic', cascade="all, delete-orphan")
    document_reviews_written = db.relationship('DocumentReview', back_populates='user', lazy='dynamic', cascade="all, delete-orphan")


    created_at = db.Column(db.DateTime, default=db.func.current_timestamp())
    updated_at = db.Column(db.DateTime, default=db.func.current_timestamp(), onupdate=db.func.current_timestamp())

    def set_password(self, password):
        self.password_hash = generate_password_hash(password)

    def check_password(self, password):
        return check_password_hash(self.password_hash, password)

    def __repr__(self):
        return f'<User {self.username}>'
