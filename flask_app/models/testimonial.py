from flask_app.extensions import db
from sqlalchemy import func

class Testimonial(db.Model):
    __tablename__ = 'testimonials'

    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_testimonial_user_id'), nullable=False, index=True)

    review_text = db.Column(db.Text, nullable=False)
    is_published = db.Column(db.Boolean, default=False, nullable=False)

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationship to User
    user = db.relationship('User', back_populates='testimonials_given')

    def __repr__(self):
        return f'<Testimonial {self.id} by User {self.user_id} - Published: {self.is_published}>'
