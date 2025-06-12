from flask_app.extensions import db
from sqlalchemy import func

class AppUsage(db.Model):
    __tablename__ = 'app_usage'

    id = db.Column(db.Integer, primary_key=True)
    # api_key is a string here. If it were a direct FK to User.api_key, User.api_key would need to be a primary or unique key
    # and this column type would match. For now, just storing the string value of the key used.
    api_key = db.Column(db.String(120), nullable=True, index=True)

    ip_address = db.Column(db.String(100), nullable=True) # Can store IPv4 or IPv6
    signature = db.Column(db.String(255), nullable=True) # For request signing, if used
    caller_info = db.Column(db.String(500), nullable=True) # User-agent or other client info

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now()) # Though usage records might often be immutable

    # Optional: Relationship to User if api_key is reliably linked
    # user = db.relationship('User', foreign_keys=[api_key], primaryjoin='AppUsage.api_key == User.api_key', backref='api_usages')
    # This requires User.api_key to be unique and indexed appropriately for joins.

    def __repr__(self):
        return f'<AppUsage {self.id} - API Key: {self.api_key}, IP: {self.ip_address} at {self.created_at}>'
