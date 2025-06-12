from flask_app.extensions import db
from sqlalchemy import func

class Contact(db.Model):
    __tablename__ = 'contacts'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(150), nullable=False)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_contact_user_id'), nullable=True, index=True) # Nullable if non-users can contact
    email = db.Column(db.String(120), nullable=False)
    subject = db.Column(db.String(255), nullable=False)
    message = db.Column(db.Text, nullable=False)

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationship to User
    user = db.relationship('User', back_populates='contact_submissions')

    def __repr__(self):
        return f'<Contact {self.id} by {self.name} ({self.email}) - Subject: {self.subject}>'
