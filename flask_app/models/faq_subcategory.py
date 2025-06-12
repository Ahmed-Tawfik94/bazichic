from flask_app.extensions import db
from sqlalchemy import func

class FaqSubCategory(db.Model):
    __tablename__ = 'faq_subcategories'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(150), nullable=False)

    faq_category_id = db.Column(db.Integer, db.ForeignKey('faq_categories.id'), nullable=False, index=True)
    # faq_category relationship is defined by backref in FaqCategory

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationship to Faq (one-to-many)
    faqs = db.relationship('Faq', backref='faq_subcategory', lazy='dynamic', foreign_keys='Faq.faq_subcategory_id', cascade="all, delete-orphan")

    def __repr__(self):
        return f'<FaqSubCategory {self.name} (Category: {self.faq_category_id})>'
