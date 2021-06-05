object Form1: TForm1
  Left = 0
  Top = 0
  Caption = #1050#1072#1083#1100#1082#1091#1083#1103#1090#1086#1088
  ClientHeight = 265
  ClientWidth = 203
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -19
  Font.Name = 'Tahoma'
  Font.Style = []
  OldCreateOrder = False
  DesignSize = (
    203
    265)
  PixelsPerInch = 96
  TextHeight = 23
  object txtResult: TEdit
    Left = 8
    Top = 8
    Width = 187
    Height = 31
    Anchors = [akLeft, akTop, akRight]
    ReadOnly = True
    TabOrder = 0
    Text = '0'
  end
  object btnBackspace: TButton
    Left = 8
    Top = 45
    Width = 41
    Height = 36
    Caption = #8592
    TabOrder = 1
    OnClick = btnBackspaceClick
  end
  object btnDelete: TButton
    Left = 55
    Top = 45
    Width = 41
    Height = 36
    Caption = #1057
    TabOrder = 2
    OnClick = btnDeleteClick
  end
  object btnSwap: TButton
    Left = 102
    Top = 45
    Width = 41
    Height = 36
    Caption = '+-'
    TabOrder = 3
    OnClick = btnSwapClick
  end
  object btnDiv: TButton
    Left = 149
    Top = 45
    Width = 41
    Height = 36
    Anchors = [akLeft, akTop, akRight]
    Caption = '/'
    TabOrder = 4
    OnClick = btnCharClick
  end
  object btnSeven: TButton
    Left = 8
    Top = 87
    Width = 41
    Height = 36
    Caption = '7'
    TabOrder = 5
    OnClick = btnNumberClick
  end
  object btnEight: TButton
    Left = 55
    Top = 87
    Width = 41
    Height = 36
    Caption = '8'
    TabOrder = 6
    OnClick = btnNumberClick
  end
  object btnNine: TButton
    Left = 102
    Top = 87
    Width = 41
    Height = 36
    Caption = '9'
    TabOrder = 7
    OnClick = btnNumberClick
  end
  object btnFour: TButton
    Left = 8
    Top = 129
    Width = 41
    Height = 36
    Caption = '4'
    TabOrder = 8
    OnClick = btnNumberClick
  end
  object btnFive: TButton
    Left = 55
    Top = 129
    Width = 41
    Height = 36
    Caption = '5'
    TabOrder = 9
    OnClick = btnNumberClick
  end
  object btnSix: TButton
    Left = 102
    Top = 129
    Width = 41
    Height = 36
    Caption = '6'
    TabOrder = 10
    OnClick = btnNumberClick
  end
  object btnOne: TButton
    Tag = 1
    Left = 8
    Top = 171
    Width = 41
    Height = 36
    Caption = '1'
    TabOrder = 11
    OnClick = btnNumberClick
  end
  object btnTwo: TButton
    Left = 55
    Top = 171
    Width = 41
    Height = 36
    Caption = '2'
    TabOrder = 12
    OnClick = btnNumberClick
  end
  object btnThree: TButton
    Left = 102
    Top = 171
    Width = 41
    Height = 36
    Caption = '3'
    TabOrder = 13
    OnClick = btnNumberClick
  end
  object btnZero: TButton
    Left = 8
    Top = 213
    Width = 88
    Height = 36
    Caption = '0'
    TabOrder = 14
    OnClick = btnNumberClick
  end
  object btnDote: TButton
    Left = 102
    Top = 213
    Width = 41
    Height = 36
    Caption = ','
    TabOrder = 15
    OnClick = btnDoteClick
  end
  object btnMult: TButton
    Left = 149
    Top = 87
    Width = 41
    Height = 36
    Anchors = [akLeft, akTop, akRight]
    Caption = '*'
    TabOrder = 16
    OnClick = btnCharClick
  end
  object btnMinus: TButton
    Left = 149
    Top = 129
    Width = 41
    Height = 36
    Anchors = [akLeft, akTop, akRight]
    Caption = '-'
    TabOrder = 17
    OnClick = btnCharClick
  end
  object btnPlus: TButton
    Left = 149
    Top = 171
    Width = 41
    Height = 36
    Anchors = [akLeft, akTop, akRight]
    Caption = '+'
    TabOrder = 18
    OnClick = btnCharClick
  end
  object btnEqual: TButton
    Left = 149
    Top = 213
    Width = 41
    Height = 36
    Anchors = [akLeft, akTop, akRight]
    Caption = '='
    TabOrder = 19
    OnClick = btnEqualClick
  end
end
