from flask_app.extensions import db
from sqlalchemy import func

class FaqCategory(db.Model):
    __tablename__ = 'faq_categories'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(150), nullable=False, unique=True)

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationship to FaqSubCategory (one-to-many)
    faq_subcategories = db.relationship('FaqSubCategory', backref='faq_category', lazy='dynamic', cascade="all, delete-orphan")
    # Relationship to Faq (one-to-many, if FAQs can belong directly to a category without a subcategory)
    faqs = db.relationship('Faq', backref='faq_category', lazy='dynamic', foreign_keys='Faq.faq_category_id', cascade="all, delete-orphan")


    def __repr__(self):
        return f'<FaqCategory {self.name}>'
