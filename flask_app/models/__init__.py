# Import models here to make them easily accessible and ensure they are registered.

from .user import User, user_roles # user_roles if it's used directly elsewhere
from .role import Role
from .document import Document
from .plan import Plan
from .subscription import Subscription
from .category import Category
from .document_type import DocumentType
from .faq_category import FaqCategory
from .faq_subcategory import FaqSubCategory
from .faq import Faq
from .payment import Payment

# You can also define __all__ to control what `from .models import *` imports
__all__ = [
    'User', 'user_roles',
    'Role',
    'Document',
    'Plan',
    'Subscription',
    'Category',
    'DocumentType',
    'FaqCategory',
    'FaqSubCategory',
    'Faq',
    'Payment',
    'Notification',
    'DocumentLike',
    'RewardPoint',
    'Referral',
    'RedeemTransaction',
    'Activity',
    'DocumentReview',
    'SiteSetting',
    'DocKeyword',
    'DocumentAudio',
    'DocumentSave',
    'DocumentView',
    'EmailVerification',
    'Contact',
    'Invoice',
    'Status',
    'StoreTag',
    'FreeTrial',
    'AppUsage',
    'Testimonial',
    'Currency'
]
