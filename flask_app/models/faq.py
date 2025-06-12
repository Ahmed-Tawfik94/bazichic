from flask_app.extensions import db
from sqlalchemy import func

class Faq(db.Model):
    __tablename__ = 'faqs'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(255), nullable=False)
    description = db.Column(db.Text, nullable=False)
    qcode = db.Column(db.String(50), unique=True, nullable=True, index=True)
    url = db.Column(db.String(255), nullable=True) # Optional vanity URL
    is_published = db.Column(db.Boolean, default=True)

    faq_category_id = db.Column(db.Integer, db.ForeignKey('faq_categories.id'), nullable=False, index=True)
    # faq_category relationship is defined by backref in FaqCategory

    faq_subcategory_id = db.Column(db.Integer, db.ForeignKey('faq_subcategories.id'), nullable=True, index=True)
    # faq_subcategory relationship is defined by backref in FaqSubCategory

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    def __repr__(self):
        return f'<Faq {self.title}>'
