from flask_app.extensions import db
from sqlalchemy import func

class DocumentAudio(db.Model):
    __tablename__ = 'document_audios'

    id = db.Column(db.Integer, primary_key=True)
    document_id = db.Column(db.Integer, db.ForeignKey('documents.id', name='fk_documentaudio_document_id'), nullable=False, index=True)

    title = db.Column(db.String(255), nullable=False)
    file_path = db.Column(db.String(500), nullable=False) # Path to the audio file
    sequence_number = db.Column(db.Integer, nullable=False, default=0) # For ordering audio tracks (sno)

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationship to Document
    document = db.relationship('Document', back_populates='audio_files')

    def __repr__(self):
        return f'<DocumentAudio {self.id}: {self.title} for Document {self.document_id}>'
